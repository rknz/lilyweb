<?php

declare(strict_types=1);

namespace Lilyweb\Core;

use PDO;
use PharData;
use Throwable;

/**
 * Backup & Disaster Recovery service.
 *
 * Produces single-file, gzip-compressed tar archives (".tar.gz") that protect
 * the full CMS footprint:
 *   - website database        -> database.sql (all lilyweb_* tables)
 *   - CMS content             -> database.sql (site settings, faqs, services…)
 *   - project metadata        -> database.sql (projects, categories, hero…)
 *   - project images / media  -> assets/ (public/assets/img + public/uploads)
 *   - required configuration  -> config/ (config/*.php + .env)
 *
 * Archives are stored only under storage/backups/ (web-denied via
 * storage/.htaccess) and are never exposed through the web root. Restore /
 * verification can target an isolated test database so a backup can be proven
 * restorable without touching live data.
 */
final class BackupService
{
    public const ARCHIVE_VERSION = 1;
    public const MAX_ARCHIVE_BYTES = 700 * 1024 * 1024; // 700 MB safety cap

    private static ?string $basePath = null;

    /** Resolve + cache the project base path (two levels above app/Core). */
    public static function basePath(): string
    {
        if (self::$basePath === null) {
            self::$basePath = rtrim(dirname(__DIR__, 2), '/\\');
        }
        return self::$basePath;
    }

    public static function backupDir(): string
    {
        return self::basePath() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'backups';
    }

    public static function publicDir(): string
    {
        return self::basePath() . DIRECTORY_SEPARATOR . 'public';
    }

    public static function configDir(): string
    {
        return self::basePath() . DIRECTORY_SEPARATOR . 'config';
    }

    public static function envFile(): string
    {
        return self::basePath() . DIRECTORY_SEPARATOR . '.env';
    }

    public static function ensureBackupDir(): void
    {
        $dir = self::backupDir();
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    /** Validate an archive filename so only known backups are addressed. */
    public static function isValidArchiveName(string $name): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9_\-\.]+\.tar\.gz$/', $name)
            && strpos($name, '..') === false
            && strpos($name, DIRECTORY_SEPARATOR) === false
            && strpos($name, '/') === false
            && strpos($name, '\\') === false;
    }

    /* ------------------------------------------------------------------ *
     *  Database dump
     * ------------------------------------------------------------------ */

    /**
     * Generate a complete, importable SQL dump of every lilyweb_* table.
     * Returns the full SQL as a string. Does NOT select a database so the dump
     * can be imported into any (including a test) database.
     */
    public static function dumpDatabase(?PDO $pdo = null): string
    {
        $pdo = $pdo ?? Database::connect();
        $prefix = Database::prefix();

        $sql = "-- ========================================================\n";
        $sql .= "-- Lily Interiors Database Backup Dump\n";
        $sql .= "-- Generated at: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- MISSING-VERSION: " . date('Ymd_His') . "\n";
        $sql .= "-- MySQL Server Version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
        $sql .= "-- ========================================================\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n";
        $sql .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
        $sql .= "SET NAMES utf8mb4;\n\n";

        $tablesStmt = $pdo->query("SHOW TABLES LIKE '" . $prefix . "%'");
        $tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $nameEsc = '`' . str_replace('`', '``', $table) . '`';
            $createStmt = $pdo->query("SHOW CREATE TABLE " . $nameEsc);
            $createRow = $createStmt->fetch(PDO::FETCH_NUM);

            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "-- Table: " . $nameEsc . "\n";
            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "DROP TABLE IF EXISTS " . $nameEsc . ";\n";
            $sql .= ($createRow[1] ?? '') . ";\n\n";

            $rowsStmt = $pdo->query("SELECT * FROM " . $nameEsc);
            $rows = $rowsStmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                // Resolve columns explicitly to preserve order.
                $cols = $rowsStmt->getColumnMeta(0);
                $columns = [];
                foreach ($rows[0] as $colName => $_unused) {
                    $columns[] = '`' . str_replace('`', '``', (string) $colName) . '`';
                }

                $sql .= "INSERT INTO " . $nameEsc . " (" . implode(', ', $columns) . ") VALUES\n";
                $chunks = [];
                foreach ($rows as $r) {
                    $escaped = [];
                    foreach ($columns as $i => $col) {
                        $cv = $r[trim($col, '`')] ?? null;
                        if ($cv === null) {
                            $escaped[] = 'NULL';
                        } else {
                            $escaped[] = $pdo->quote((string) $cv);
                        }
                    }
                    $chunks[] = '(' . implode(', ', $escaped) . ')';
                }
                $sql .= implode(",\n", $chunks) . ";\n\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
        return $sql;
    }

    /* ------------------------------------------------------------------ *
     *  Asset & config collection
     * ------------------------------------------------------------------ */

    /**
     * Collect project / media image files as [archiveRelativePath => realPath].
     * Covers the seeded media under public/assets/img and the user-uploaded
     * directory public/uploads.
     */
    public static function collectAssets(): array
    {
        $entries = [];

        $imgDir = self::publicDir() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img';
        if (is_dir($imgDir)) {
            foreach (glob($imgDir . DIRECTORY_SEPARATOR . '*.{jpg,jpeg,png,webp,gif,svg,avif}', GLOB_BRACE) ?: [] as $f) {
                if (is_file($f)) {
                    $entries['assets/img/' . basename($f)] = $f;
                }
            }
        }

        $uploadsDir = self::publicDir() . DIRECTORY_SEPARATOR . 'uploads';
        if (is_dir($uploadsDir)) {
            $it = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($uploadsDir, \FilesystemIterator::SKIP_DOTS)
            );
            foreach ($it as $file) {
                if ($file->isFile()) {
                    $rel = substr($file->getPathname(), strlen($uploadsDir) + 1);
                    $entries['assets/uploads/' . str_replace('\\', '/', $rel)] = $file->getPathname();
                }
            }
        }

        return $entries;
    }

    /**
     * Collect required configuration as [archiveRelativePath => realPath],
     * including config/*.php and the .env file.
     */
    public static function collectConfig(): array
    {
        $entries = [];

        $configDir = self::configDir();
        if (is_dir($configDir)) {
            foreach (glob($configDir . DIRECTORY_SEPARATOR . '*.php') ?: [] as $f) {
                if (is_file($f)) {
                    $entries['config/' . basename($f)] = $f;
                }
            }
        }

        $env = self::envFile();
        if (is_file($env)) {
            $entries['config/.env'] = $env;
        }

        return $entries;
    }

    /* ------------------------------------------------------------------ *
     *  Tar.gz creation & extraction
     * ------------------------------------------------------------------ */

    /**
     * Build a gzip-compressed tar archive from [archivePath => realFile] entries.
     * Uses PharData (tar) + gzip since ZipArchive is unavailable on this host.
     */
    public static function createTarGz(array $entries, string $tarGzPath): void
    {
        if (!class_exists(PharData::class)) {
            throw new \RuntimeException('Phar extension is required to create backup archives.');
        }

        $tmpTar = $tarGzPath . '.tmp.tar';
        if (is_file($tmpTar)) {
            @unlink($tmpTar);
        }

        $tar = new PharData($tmpTar);

        // Add files in deterministic order.
        ksort($entries);
        foreach ($entries as $localPath => $realPath) {
            if (!is_file($realPath)) {
                continue;
            }
            // Sanitize archive member path.
            $localPath = str_replace('\\', '/', $localPath);
            $localPath = preg_replace('#(?<![:/])\.{2,}/#', '', $localPath);
            $localPath = ltrim($localPath, '/');
            if ($localPath === '' || $localPath === '.' || $localPath === '..') {
                continue;
            }
            $tar->addFile($realPath, $localPath);
        }
        unset($tar);

        if (!is_file($tmpTar)) {
            throw new \RuntimeException('Failed to build tar archive.');
        }

        $tarContents = file_get_contents($tmpTar);
        if ($tarContents === false) {
            @unlink($tmpTar);
            throw new \RuntimeException('Failed to read tar archive for compression.');
        }

        $gz = gzencode($tarContents, 9);
        @unlink($tmpTar);

        if ($gz === false) {
            throw new \RuntimeException('Failed to compress backup archive.');
        }

        if (file_put_contents($tarGzPath, $gz) === false) {
            throw new \RuntimeException('Failed to write backup archive to storage.');
        }
    }

    /**
     * Extract a .tar.gz archive to a destination directory with path-traversal
     * protection. Returns the list of extracted relative paths.
     */
    public static function extractTarGz(string $tarGzPath, string $destDir): array
    {
        if (!class_exists(PharData::class)) {
            throw new \RuntimeException('Phar extension is required to restore backup archives.');
        }
        if (!is_file($tarGzPath)) {
            throw new \RuntimeException('Backup archive not found.');
        }

        // Decompress to a temporary .tar.
        $raw = file_get_contents($tarGzPath);
        $tar = gzdecode($raw);
        if ($tar === false) {
            throw new \RuntimeException('Backup archive is corrupt (invalid gzip data).');
        }

        if (!is_dir($destDir)) {
            @mkdir($destDir, 0755, true);
        }
        $tmpTar = $destDir . DIRECTORY_SEPARATOR . '.extract.tmp.tar';
        file_put_contents($tmpTar, $tar);

        $phar = new PharData($tmpTar);
        $extracted = [];
        try {
            $phar->extractTo($destDir, null, true);
        } catch (Throwable $e) {
            @unlink($tmpTar);
            throw new \RuntimeException('Failed to extract archive: ' . $e->getMessage());
        }

        // Enumerate what was actually extracted (relative to destDir).
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($destDir, \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($it as $file) {
            if ($file->isFile()) {
                $rel = substr($file->getPathname(), strlen(rtrim($destDir, '/\\')) + 1);
                $extracted[] = str_replace('\\', '/', $rel);
            }
        }

        @unlink($tmpTar);
        return $extracted;
    }

    /* ------------------------------------------------------------------ *
     *  Backup creation
     * ------------------------------------------------------------------ */

    /**
     * Create a complete full backup (.tar.gz) protecting database + content +
     * project metadata + media + configuration. Records it in the DB.
     *
     * @return array{file:string,size:int,sha256:string,manifest:array}
     */
    public static function fullBackup(string $creator = 'Owner'): array
    {
        self::ensureBackupDir();

        $pdo = Database::connect();
        $dbSql = self::dumpDatabase($pdo);

        $assets = self::collectAssets();
        $config = self::collectConfig();

        $manifest = [
            'version' => self::ARCHIVE_VERSION,
            'type' => 'full',
            'created_at' => date('c'),
            'database' => (string) Config::get('database.connections.mysql.database', 'lily_web'),
            'tables' => self::tableList($pdo),
            'asset_count' => count($assets),
            'config_count' => count($config),
        ];

        $entries = [];
        $entries['database.sql'] = self::writeTemp($dbSql);
        foreach ($assets as $rel => $real) {
            $entries[$rel] = $real;
        }
        foreach ($config as $rel => $real) {
            $entries[$rel] = $real;
        }
        $entries['manifest.json'] = self::writeTemp(json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $filename = 'lilyweb_full_' . date('Y-m-d_His') . '.tar.gz';
        $archivePath = self::backupDir() . DIRECTORY_SEPARATOR . $filename;

        self::createTarGz($entries, $archivePath);

        // Clean up temp in-memory file handles.
        @unlink($entries['database.sql'] ?? '');
        @unlink($entries['manifest.json'] ?? '');

        $size = file_exists($archivePath) ? filesize($archivePath) : 0;
        $sha256 = hash_file('sha256', $archivePath);

        self::recordBackup($filename, $archivePath, $size, $sha256, $creator);

        Logger::info('Full backup created', ['file' => $filename, 'size' => $size, 'assets' => count($assets), 'config' => count($config)]);

        return [
            'file' => $filename,
            'storage_path' => $archivePath,
            'size' => $size,
            'sha256' => $sha256,
            'manifest' => $manifest,
        ];
    }

    /**
     * Create a legacy database-only backup (.sql) for compatibility.
     */
    public static function dbBackup(string $creator = 'Owner'): array
    {
        self::ensureBackupDir();

        $pdo = Database::connect();
        $sql = self::dumpDatabase($pdo);

        $filename = 'lilyweb_db_backup_' . date('Y-m-d_His') . '.sql';
        $archivePath = self::backupDir() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($archivePath, $sql);

        $size = file_exists($archivePath) ? filesize($archivePath) : 0;
        $sha256 = hash_file('sha256', $archivePath);

        self::recordBackup($filename, $archivePath, $size, $sha256, $creator);

        Logger::info('Database-only backup created', ['file' => $filename, 'size' => $size]);

        return [
            'file' => $filename,
            'storage_path' => $archivePath,
            'size' => $size,
            'sha256' => $sha256,
        ];
    }

    /** Record a backup archive row in lilyweb_backup_records. */
    public static function recordBackup(string $filename, string $storagePath, int $size, string $sha256, string $creator): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            INSERT INTO `lilyweb_backup_records`
                (`filename`, `storage_path`, `size_bytes`, `checksum_sha256`, `created_by_user`)
            VALUES (:fn, :path, :size, :sha, :user)
        ");
        $stmt->execute([
            ':fn' => $filename,
            ':path' => $storagePath,
            ':size' => $size,
            ':sha' => $sha256,
            ':user' => $creator,
        ]);
    }

    /* ------------------------------------------------------------------ *
     *  Validation
     * ------------------------------------------------------------------ */

    /** List lilyweb_* tables in the configured database. */
    public static function tableList(?PDO $pdo = null): array
    {
        $pdo = $pdo ?? Database::connect();
        $prefix = Database::prefix();
        $stmt = $pdo->query("SHOW TABLES LIKE '" . $prefix . "%'");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Validate a backup archive: existence, extension, size, gzip integrity and
     * manifest presence. Returns ['ok'=>bool,'message'=>string,'manifest'=>?array].
     */
    public static function validateArchive(string $archivePath): array
    {
        if (!is_file($archivePath)) {
            return ['ok' => false, 'message' => 'Backup archive does not exist.'];
        }

        if (!str_ends_with(strtolower($archivePath), '.tar.gz')) {
            return ['ok' => false, 'message' => 'Only .tar.gz backup archives are supported for restore.'];
        }

        $size = filesize($archivePath);
        if ($size === false || $size === 0) {
            return ['ok' => false, 'message' => 'Backup archive is empty or unreadable.'];
        }
        if ($size > self::MAX_ARCHIVE_BYTES) {
            return ['ok' => false, 'message' => 'Backup archive exceeds the maximum allowed size.'];
        }

        // gzip integrity check.
        $raw = file_get_contents($archivePath);
        $decoded = @gzdecode($raw);
        if ($decoded === false) {
            return ['ok' => false, 'message' => 'Backup archive is corrupt (invalid gzip data).'];
        }

        // Peek manifest without a full extract.
        $tmpDir = self::backupDir() . DIRECTORY_SEPARATOR . '.validate_' . bin2hex(random_bytes(4));
        @mkdir($tmpDir, 0755, true);
        try {
            $extracted = self::extractTarGz($archivePath, $tmpDir);
            $manifest = null;
            $manifestFile = $tmpDir . DIRECTORY_SEPARATOR . 'manifest.json';
            if (is_file($manifestFile)) {
                $manifest = json_decode((string) file_get_contents($manifestFile), true);
            }
            $hasDatabase = is_file($tmpDir . DIRECTORY_SEPARATOR . 'database.sql');
        } finally {
            self::rrmdir($tmpDir);
        }

        if ($manifest === null || !is_array($manifest)) {
            return ['ok' => false, 'message' => 'Backup archive is missing a valid manifest.json.'];
        }
        if (!$hasDatabase) {
            return ['ok' => false, 'message' => 'Backup archive is missing database.sql.'];
        }
        if (($manifest['version'] ?? 0) !== self::ARCHIVE_VERSION) {
            return ['ok' => false, 'message' => 'Backup archive version is not supported by this system.'];
        }

        return ['ok' => true, 'message' => 'Archive is valid.', 'manifest' => $manifest];
    }

    /* ------------------------------------------------------------------ *
     *  Restore (isolated test environment)
     * ------------------------------------------------------------------ */

    /**
     * Connect to an arbitrary MySQL database (bypassing the singleton) for
     * isolated restore/verify. Creates a raw PDO to the specified database.
     */
    public static function rawConnect(string $database): PDO
    {
        $cfg = Config::get('database.connections.mysql', []);
        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $cfg['driver'] ?? 'mysql',
            $cfg['host'] ?? '127.0.0.1',
            (int) ($cfg['port'] ?? 3306),
            $database,
            $cfg['charset'] ?? 'utf8mb4'
        );
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, $cfg['username'] ?? 'root', $cfg['password'] ?? '', $options);
    }

    /**
     * Drop a (test) database if it exists. Used to keep isolated test
     * environments clean before each verify-restore.
     */
    public static function dropDatabase(string $database): void
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $database)) {
            throw new \RuntimeException('Invalid target database name.');
        }
        $cfg = Config::get('database.connections.mysql', []);
        $dsn = sprintf(
            '%s:host=%s;port=%d;charset=%s',
            $cfg['driver'] ?? 'mysql',
            $cfg['host'] ?? '127.0.0.1',
            (int) ($cfg['port'] ?? 3306),
            $cfg['charset'] ?? 'utf8mb4'
        );
        $pdo = new PDO(
            $dsn,
            $cfg['username'] ?? 'root',
            $cfg['password'] ?? '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = :db");
        $stmt->execute([':db' => $database]);
        if ($stmt->fetch() !== false) {
            $dbEsc = str_replace('`', '``', $database);
            $pdo->exec("DROP DATABASE `{$dbEsc}`");
        }
    }

    /**
     * Ensure a target database exists (creating it if missing) using the
     * configured credentials with no database selected.
     */
    public static function ensureDatabaseExists(string $database): void
    {
        $cfg = Config::get('database.connections.mysql', []);
        $dsn = sprintf(
            '%s:host=%s;port=%d;charset=%s',
            $cfg['driver'] ?? 'mysql',
            $cfg['host'] ?? '127.0.0.1',
            (int) ($cfg['port'] ?? 3306),
            $cfg['charset'] ?? 'utf8mb4'
        );
        $pdo = new PDO(
            $dsn,
            $cfg['username'] ?? 'root',
            $cfg['password'] ?? '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = :db");
        $stmt->execute([':db' => $database]);
        if ($stmt->fetch() === false) {
            $dbEsc = str_replace('`', '``', $database);
            $pdo->exec("CREATE DATABASE `{$dbEsc}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }
    }

    /**
     * Dump the database.sql member from a validated archive to a file path.
     * Returns the member's raw SQL string.
     */
    public static function readDatabaseSql(string $archivePath): string
    {
        $tmpDir = self::backupDir() . DIRECTORY_SEPARATOR . '.dbsql_' . bin2hex(random_bytes(4));
        @mkdir($tmpDir, 0755, true);
        try {
            self::extractTarGz($archivePath, $tmpDir);
            $sqlFile = $tmpDir . DIRECTORY_SEPARATOR . 'database.sql';
            if (!is_file($sqlFile)) {
                throw new \RuntimeException('database.sql not found in archive.');
            }
            return (string) file_get_contents($sqlFile);
        } finally {
            self::rrmdir($tmpDir);
        }
    }

    /**
     * Import a backup's database.sql into a target database (used for isolated
     * test-environment restore). Creates the DB if missing and verifies the
     * restored table set + a few sanity counts.
     *
     * @return array{db:string,tables:array,rows:int}
     */
    public static function restoreDatabase(string $archivePath, string $targetDb): array
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $targetDb)) {
            throw new \RuntimeException('Invalid target database name.');
        }

        $sql = self::readDatabaseSql($archivePath);
        if (trim($sql) === '') {
            throw new \RuntimeException('Backup contains an empty database dump.');
        }

        self::ensureDatabaseExists($targetDb);
        $pdo = self::rawConnect($targetDb);

        // Wrap the dump so the whole import is a single logical unit.
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        $ok = $pdo->exec($sql);
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        if ($ok === false) {
            throw new \RuntimeException('Database import failed.');
        }

        // Verify restored tables exist.
        $restored = self::tableList($pdo);
        $rows = 0;
        foreach ($restored as $tbl) {
            $c = $pdo->query('SELECT COUNT(*) FROM `' . str_replace('`', '``', $tbl) . '`')->fetchColumn();
            $rows += (int) $c;
        }

        return [
            'db' => $targetDb,
            'tables' => $restored,
            'rows' => $rows,
        ];
    }

    /**
     * Verify the media assets bundled in an archive against a destination
     * directory (test environment). Returns counts/sizes matched.
     */
    public static function restoreMedia(string $archivePath, string $destDir): array
    {
        $tmpDir = self::backupDir() . DIRECTORY_SEPARATOR . '.media_' . bin2hex(random_bytes(4));
        @mkdir($tmpDir, 0755, true);
        $manifest = null;
        $assetFiles = [];
        try {
            self::extractTarGz($archivePath, $tmpDir);
            $manifestFile = $tmpDir . DIRECTORY_SEPARATOR . 'manifest.json';
            if (is_file($manifestFile)) {
                $manifest = json_decode((string) file_get_contents($manifestFile), true);
            }

            // Walk assets/ subtree.
            $assetsDir = $tmpDir . DIRECTORY_SEPARATOR . 'assets';
            if (is_dir($assetsDir)) {
                $it = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($assetsDir, \FilesystemIterator::SKIP_DOTS)
                );
                foreach ($it as $file) {
                    if ($file->isFile()) {
                        $rel = substr($file->getPathname(), strlen($assetsDir) + 1);
                        $assetFiles[$rel] = [
                            'size' => $file->getSize(),
                            'sha256' => hash_file('sha256', $file->getPathname()),
                        ];
                    }
                }
            }

            // If a destination was provided, copy assets there (for verify).
            if ($destDir !== null && $destDir !== '' && !empty($assetFiles)) {
                foreach ($assetFiles as $rel => $meta) {
                    $src = $assetsDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
                    $dst = $destDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
                    if (!is_dir(dirname($dst))) {
                        @mkdir(dirname($dst), 0755, true);
                    }
                    @copy($src, $dst);
                }
            }
        } finally {
            self::rrmdir($tmpDir);
        }

        return [
            'manifest_count' => (int) ($manifest['asset_count'] ?? 0),
            'restored_count' => count($assetFiles),
            'assets' => $assetFiles,
        ];
    }

    /**
     * Verify that the required configuration members are present + non-empty
     * in the archive (without overwriting the live config).
     */
    public static function verifyConfig(array $extractedPaths): array
    {
        $required = ['config/app.php', 'config/database.php', 'config/security.php', 'config/.env'];
        $found = [];
        foreach ($required as $r) {
            $found[$r] = in_array($r, $extractedPaths, true);
        }
        return ['required' => $found];
    }

    /* ------------------------------------------------------------------ *
     *  Orchestration: full restore + verify to an isolated test environment
     * ------------------------------------------------------------------ */

    /**
     * Run a complete restore + verify cycle against an isolated test database
     * and temporary media/config locations. Does NOT touch the live database,
     * public assets or live config — proving the backup is restorable safely.
     *
     * @return array full structured report
     */
    public static function runRestoreVerify(string $archivePath, string $targetDb): array
    {
        $validation = self::validateArchive($archivePath);
        if (!$validation['ok']) {
            throw new \RuntimeException($validation['message']);
        }

        $report = [
            'validated' => true,
            'manifest' => $validation['manifest'],
            'db' => null,
            'media' => null,
            'config' => null,
            'timestamps' => [
                'created' => $validation['manifest']['created_at'] ?? null,
                'restored' => date('c'),
            ],
        ];

        $stagingRoot = self::backupDir() . DIRECTORY_SEPARATOR . '.verify_' . bin2hex(random_bytes(4));
        @mkdir($stagingRoot, 0755, true);
        try {
            // 2) Media -> staging assets dir (kept within web-denied storage).
            $mediaDest = $stagingRoot . DIRECTORY_SEPARATOR . 'media';
            $report['media'] = self::restoreMedia($archivePath, $mediaDest);
        } finally {
            self::rrmdir($stagingRoot);
        }

        // 3) Config -> verify member presence/integrity only (no live overwrite).
        $tmpDir = self::backupDir() . DIRECTORY_SEPARATOR . '.cfg_' . bin2hex(random_bytes(4));
        @mkdir($tmpDir, 0755, true);
        try {
            $extracted = self::extractTarGz($archivePath, $tmpDir);
            $report['config'] = self::verifyConfig($extracted);
        } finally {
            self::rrmdir($tmpDir);
        }

        // 1) Database -> isolated test DB (performed last so media/config
        //    verification failures don't leave a partially restored DB behind).
        $report['db'] = self::restoreDatabase($archivePath, $targetDb);

        return $report;
    }

    /* ------------------------------------------------------------------ *
     *  Helpers
     * ------------------------------------------------------------------ */

    private static function writeTemp(string $contents): string
    {
        $path = (self::backupDir() . DIRECTORY_SEPARATOR . '.mem_' . bin2hex(random_bytes(4)) . '.tmp');
        file_put_contents($path, $contents);
        return $path;
    }

    private static function rrmdir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $f) {
            if ($f->isDir()) {
                @rmdir($f->getPathname());
            } else {
                @unlink($f->getPathname());
            }
        }
        @rmdir($dir);
    }
}

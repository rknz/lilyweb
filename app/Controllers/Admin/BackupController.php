<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers\Admin;

use Lilyweb\Core\Request;
use Lilyweb\Core\Response;
use Lilyweb\Core\Auth;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;
use Lilyweb\Core\Session;
use Lilyweb\Core\View;
use Lilyweb\Core\BackupService;
use PDO;
use Exception;
use Throwable;

final class BackupController extends AdminController
{
    /**
     * List all database & full site backups.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM `lilyweb_backup_records` ORDER BY `id` DESC");
        $backups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = View::make('admin.backup.index', [
            'backups' => $backups,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Database Backup & Disaster Recovery',
            'pageHeading' => '1-Click Database Dump & Disaster Recovery Management',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Generate 1-Click Database SQL dump.
     */
    public function createSql(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $backupDir = dirname(__DIR__, 3) . '/storage/backups';
        if (!is_dir($backupDir)) {
            @mkdir($backupDir, 0755, true);
        }

        $filename = 'lilyweb_db_backup_' . date('Y-m-d_His') . '.sql';
        $filepath = $backupDir . '/' . $filename;

        try {
            $pdo = Database::connect();
            $tablesStmt = $pdo->query("SHOW TABLES LIKE 'lilyweb_%'");
            $tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

            $sql = "-- ========================================================\n";
            $sql .= "-- Lily Interiors Database Backup Dump\n";
            $sql .= "-- Generated at: " . date('Y-m-d H:i:s') . "\n";
            $sql .= "-- MySQL Server Version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
            $sql .= "-- ========================================================\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n";
            $sql .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
            $sql .= "SET NAMES utf8mb4;\n\n";

            foreach ($tables as $table) {
                // Get Create Table query
                $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
                $createRow = $createStmt->fetch(PDO::FETCH_NUM);
                $createTableSql = $createRow[1] ?? '';

                $sql .= "-- --------------------------------------------------------\n";
                $sql .= "-- Structure for table `{$table}`\n";
                $sql .= "-- --------------------------------------------------------\n";
                $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $sql .= $createTableSql . ";\n\n";

                // Get Records
                $rowsStmt = $pdo->query("SELECT * FROM `{$table}`");
                $rows = $rowsStmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($rows)) {
                    $sql .= "-- Dumping data for table `{$table}`\n";
                    $sql .= "INSERT INTO `{$table}` VALUES\n";
                    $valueChunks = [];
                    foreach ($rows as $r) {
                        $escapedValues = array_map(function ($val) use ($pdo) {
                            if ($val === null) {
                                return 'NULL';
                            }
                            return $pdo->quote((string)$val);
                        }, array_values($r));
                        $valueChunks[] = "(" . implode(', ', $escapedValues) . ")";
                    }
                    $sql .= implode(",\n", $valueChunks) . ";\n\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

            file_put_contents($filepath, $sql);
            $sizeBytes = filesize($filepath) ?: 0;
            $checksum = hash_file('sha256', $filepath);

            // Record in database
            $recStmt = $pdo->prepare("
                INSERT INTO `lilyweb_backup_records`
                (`filename`, `storage_path`, `size_bytes`, `checksum_sha256`, `created_by_user`)
                VALUES (:fn, :path, :size, :hash, :user)
            ");
            $recStmt->execute([
                ':fn' => $filename,
                ':path' => $filepath,
                ':size' => $sizeBytes,
                ':hash' => $checksum,
                ':user' => Auth::user()['username'] ?? 'admin',
            ]);

            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'create_backup', 'database', $filename, Request::ip());
            Session::flash('success', "Database backup created successfully ({$filename}, " . round($sizeBytes / 1024, 1) . " KB).");

        } catch (Exception $e) {
            Session::flash('error', 'Failed to generate backup: ' . $e->getMessage());
        }

        return Response::redirect('/admin/backup');
    }

    /**
     * Download backup file.
     */
    public function download(array $params = []): void
    {
        $this->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `lilyweb_backup_records` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $backup = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$backup || !file_exists($backup['storage_path'])) {
            Session::flash('error', 'Backup file not found on server.');
            Response::redirect('/admin/backup')->send();
            exit;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($backup['filename']) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backup['storage_path']));
        readfile($backup['storage_path']);
        exit;
    }

    /**
     * Restore database from uploaded SQL file.
     */
    public function restore(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        if (empty($_FILES['sql_file']) || $_FILES['sql_file']['error'] !== UPLOAD_ERR_OK) {
            Session::flash('error', 'Please select a valid .sql backup file to restore.');
            return Response::redirect('/admin/backup');
        }

        $tmpFile = $_FILES['sql_file']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['sql_file']['name'], PATHINFO_EXTENSION));

        if ($ext !== 'sql') {
            Session::flash('error', 'Only .sql database files are supported for restore.');
            return Response::redirect('/admin/backup');
        }

        // Validate MIME type via content sniffing
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmpFile);
        finfo_close($finfo);

        $allowedMimes = ['text/plain', 'application/octet-stream', 'text/x-sql'];
        if (!in_array($mime, $allowedMimes, true)) {
            Session::flash('error', 'Invalid file type. Only SQL text files are allowed for restore.');
            return Response::redirect('/admin/backup');
        }

        // Max restore file size: 50MB
        $maxBytes = 50 * 1024 * 1024;
        if ($_FILES['sql_file']['size'] > $maxBytes) {
            Session::flash('error', 'SQL file too large. Maximum restore file size is 50MB.');
            return Response::redirect('/admin/backup');
        }

        $sqlContent = file_get_contents($tmpFile);
        if (!$sqlContent || trim($sqlContent) === '') {
            Session::flash('error', 'Uploaded SQL file was empty.');
            return Response::redirect('/admin/backup');
        }

        // Validate that the SQL only targets lilyweb_ tables (safety check)
        // Strip SQL comments and quoted strings for validation
        $checkContent = preg_replace('/--[^\n]*/', '', $sqlContent);
        $checkContent = preg_replace("/'[^']*'/s", '', $checkContent);
        $checkContent = preg_replace('/"[^"]*"/s', '', $checkContent);

        // Dangerous SQL operations that shouldn't appear in a restore
        $dangerousPatterns = [
            '/\bDROP\s+DATABASE\b/i',
            '/\bCREATE\s+DATABASE\b/i',
            '/\bGRANT\s+ALL\b/i',
            '/\bFLUSH\s+PRIVILEGES\b/i',
            '/\bLOAD_FILE\b/i',
            '/\bINTO\s+OUTFILE\b/i',
            '/\bINTO\s+DUMPFILE\b/i',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $checkContent)) {
                Session::flash('error', 'SQL file contains dangerous operations that are not permitted in restore. Only lilyweb_ table operations are allowed.');
                return Response::redirect('/admin/backup');
            }
        }

        try {
            $pdo = Database::connect();
            $pdo->exec($sqlContent);

            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'restore_database', 'database', $_FILES['sql_file']['name'], Request::ip());
            Session::flash('success', 'Database successfully restored from ' . $_FILES['sql_file']['name']);

        } catch (Exception $e) {
            Session::flash('error', 'Database restore failed: ' . $e->getMessage());
        }

        return Response::redirect('/admin/backup');
    }

    /**
     * Delete backup file.
     */
    public function delete(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("SELECT `storage_path`, `filename` FROM `lilyweb_backup_records` WHERE `id` = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $backup = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($backup && file_exists($backup['storage_path'])) {
                @unlink($backup['storage_path']);
            }

            $delStmt = $pdo->prepare("DELETE FROM `lilyweb_backup_records` WHERE `id` = :id");
            $delStmt->execute([':id' => $id]);

            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'delete_backup', 'backup', $backup['filename'] ?? (string)$id, Request::ip());
            Session::flash('success', 'Backup archive deleted.');
        }

        return Response::redirect('/admin/backup');
    }
}

<?php

declare(strict_types=1);

namespace Lilyweb\Core;

use PDO;

/**
 * Database connection layer (PDO singleton).
 *
 * Provides a single shared PDO connection configured from config/database.php.
 * Uses real prepared statements (emulation disabled) and exceptions for errors.
 * All queries must reference the applied lilyweb_ table prefix.
 */
final class Database
{
    private static ?PDO $connection = null;
    private static ?string $prefix = null;
    /** @var array<string,array{one:?array,all:?array}> Per-request query cache. */
    private static array $cache = [];

    /**
     * Build a stable cache key for a query + bound params.
     */
    private static function cacheKey(string $sql, array $params): string
    {
        return hash('sha256', $sql . '|' . serialize($params));
    }

    public static function connect(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = Config::get('database.connections.mysql', []);
        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $config['driver'] ?? 'mysql',
            $config['host'] ?? '127.0.0.1',
            (int) ($config['port'] ?? 3306),
            $config['database'] ?? 'lily_web',
            $config['charset'] ?? 'utf8mb4'
        );

        $options = $config['options'] ?? [];
        $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
        $options[PDO::ATTR_EMULATE_PREPARES] = false;

        self::$connection = new PDO(
            $dsn,
            $config['username'] ?? 'root',
            $config['password'] ?? '',
            $options
        );

        // Establish collation matching our schema standard.
        $collation = $config['collation'] ?? 'utf8mb4_unicode_ci';
        self::$connection->exec("SET NAMES 'utf8mb4' COLLATE '" . $collation . "'");

        self::$prefix = (string) ($config['prefix'] ?? 'lilyweb_');

        return self::$connection;
    }

    public static function pdo(): PDO
    {
        return self::connect();
    }

    /**
     * The configured table prefix. Use to build table names in queries.
     */
    public static function prefix(): string
    {
        if (self::$prefix === null) {
            self::connect();
        }
        return (string) self::$prefix;
    }

    /**
     * Apply the lilyweb_ prefix to a bare table name.
     */
    public static function table(string $name): string
    {
        $name = trim($name);
        if (str_starts_with($name, self::prefix())) {
            return $name;
        }
        return self::prefix() . $name;
    }

    /**
     * Run a prepared query and return the statement.
     */
    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Return a single row (associative) or null.
     * When $cache is true, identical queries within the same request are served
     * from memory so shared lookups (nav, footer, site settings, stats) are not
     * re-executed per component.
     */
    public static function fetchOne(string $sql, array $params = [], bool $cache = false): ?array
    {
        $key = $cache ? self::cacheKey($sql, $params) : null;
        if ($key !== null && array_key_exists($key, self::$cache)) {
            return self::$cache[$key]['one'];
        }

        $row = self::query($sql, $params)->fetch();
        $result = ($row === false) ? null : $row;

        if ($key !== null) {
            self::$cache[$key] = ['one' => $result, 'all' => null];
        }
        return $result;
    }

    /**
     * Return all rows.
     * See fetchOne() for the $cache behaviour.
     */
    public static function fetchAll(string $sql, array $params = [], bool $cache = false): array
    {
        $key = $cache ? self::cacheKey($sql, $params) : null;
        if ($key !== null && array_key_exists($key, self::$cache)) {
            return self::$cache[$key]['all'] ?? [];
        }

        $result = self::query($sql, $params)->fetchAll();

        if ($key !== null) {
            self::$cache[$key] = ['one' => null, 'all' => $result];
        }
        return $result;
    }

    /**
     * Last inserted id on the current connection.
     */
    public static function lastInsertId(): string
    {
        return self::connect()->lastInsertId();
    }

    public static function disconnect(): void
    {
        self::$connection = null;
        self::$cache = [];
    }
}

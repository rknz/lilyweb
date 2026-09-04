<?php

declare(strict_types=1);

namespace Lilyweb\Core;

/**
 * Environment (.env) loader.
 *
 * Loads KEY=VALUE pairs from the project .env file into $_ENV / getenv()
 * without overwriting values already provided by the actual environment.
 * Values are only kept in-memory; they are never exposed via the web.
 *
 * This is part of the environment/config separation: environment-specific
 * secrets (DB credentials, session key, etc.) live in .env (git-ignored) while
 * non-secret defaults live in config/*.php.
 */
final class Env
{
    /** @var array<string,string> */
    private static array $loaded = [];

    /**
     * Load the .env file from the given base path (project root).
     */
    public static function load(string $basePath): void
    {
        $file = rtrim($basePath, '/\\') . DIRECTORY_SEPARATOR . '.env';
        if (!is_file($file)) {
            $file = rtrim($basePath, '/\\') . DIRECTORY_SEPARATOR . '.env.production';
        }

        if (!is_file($file) || !is_readable($file)) {
            // Missing .env is not fatal for local/CI bootstrap; rely on defaults
            // and real environment variables.
            return;
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and blank lines.
            if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) {
                continue;
            }

            // Ignore export-style prefix if present.
            if (str_starts_with($line, 'export ')) {
                $line = trim(substr($line, 7));
            }

            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }

            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));

            if ($key === '') {
                continue;
            }

            // Strip surrounding quotes.
            if (strlen($value) >= 2) {
                $first = $value[0];
                $last = $value[strlen($value) - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                    if ($first === '"') {
                        $value = str_replace('\\"', '"', $value);
                        $value = str_replace('\\\\', '\\', $value);
                    }
                }
            }

            $value = self::resolveLinks($value);

            // Real environment always wins over the .env file.
            if (getenv($key) !== false || array_key_exists($key, $_ENV)) {
                continue;
            }

            self::$loaded[$key] = $value;
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
    }

    /**
     * Read a value from the environment. Falls back to $default.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }
        return array_key_exists($key, self::$loaded) ? self::$loaded[$key] : $default;
    }

    /**
     * Read a boolean-ish value (1, true, yes, on = true).
     */
    public static function bool(string $key, bool $default = false): bool
    {
        $value = self::get($key);
        if ($value === null) {
            return $default;
        }
        return in_array(strtolower(trim((string) $value)), ['1', 'true', 'yes', 'on'], true);
    }

    /**
     * Resolve ${VAR} references in env values (simple interpolation).
     */
    private static function resolveLinks(string $value): string
    {
        if (!str_contains($value, '${')) {
            return $value;
        }
        return preg_replace_callback('/\$\{([A-Z0-9_]+)\}/i', static function (array $m): string {
            $ref = self::get($m[1]);
            return $ref ?? '';
        }, $value) ?? $value;
    }
}

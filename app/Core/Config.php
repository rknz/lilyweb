<?php

declare(strict_types=1);

namespace Lilyweb\Core;

/**
 * Configuration repository.
 *
 * Merges static config files (config/*.php) with runtime environment values
 * (.env / real environment). Provides dot-notation access: Config::get('database.host').
 */
final class Config
{
    /** @var array<string,mixed> */
    private static array $items = [];

    private static bool $loaded = false;

    /**
     * Load all config files from the config directory.
     */
    public static function load(string $configPath): void
    {
        if (self::$loaded) {
            return;
        }

        $configPath = rtrim($configPath, '/\\');

        foreach (glob($configPath . DIRECTORY_SEPARATOR . '*.php') ?: [] as $file) {
            $key = basename($file, '.php');
            $data = require $file;
            if (is_array($data)) {
                self::$items[$key] = $data;
            }
        }

        self::$loaded = true;
    }

    /**
     * Get a config value using dot notation. Returns $default when missing.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = self::$items;

        foreach ($segments as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }

        return $value;
    }

    public static function has(string $key): bool
    {
        return self::get($key, self::MISSING) !== self::MISSING;
    }

    public static function all(): array
    {
        return self::$items;
    }

    public static function set(string $key, mixed $value): void
    {
        $segments = explode('.', $key);
        $ref = &self::$items;
        foreach ($segments as $segment) {
            if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                $ref[$segment] = [];
            }
            $ref = &$ref[$segment];
        }
        $ref = $value;
    }

    private const MISSING = "\0__LILYWEB_MISSING__\0";
}

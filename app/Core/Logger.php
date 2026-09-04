<?php

declare(strict_types=1);

namespace Lilyweb\Core;

/**
 * File-based logger foundation.
 *
 * Writes structured JSON lines to storage/logs/*.log in our isolated namespace.
 * Level model: debug < info < notice < warning < error < critical < alert < emergency.
 */
final class Logger
{
    private static ?string $path = null;
    private static string $level = 'debug';
    private static bool $initialized = false;

    private const LEVELS = [
        'debug' => 0,
        'info' => 1,
        'notice' => 2,
        'warning' => 3,
        'error' => 4,
        'critical' => 5,
        'alert' => 6,
        'emergency' => 7,
    ];

    public static function init(string $path, string $level): void
    {
        self::$path = rtrim($path, '/\\');
        self::$level = strtolower($level);
        self::$initialized = true;

        if (!is_dir(self::$path)) {
            @mkdir(self::$path, 0775, true);
        }
    }

    public static function log(string $level, string $message, array $context = []): void
    {
        if (!self::$initialized || self::$path === null) {
            return;
        }

        $threshold = self::LEVELS[self::$level] ?? 1;
        if ((self::LEVELS[strtolower($level)] ?? 4) < $threshold) {
            return;
        }

        $entry = [
            'ts' => gmdate('Y-m-d\TH:i:s\Z'),
            'level' => strtoupper($level),
            'msg' => $message,
            'context' => $context,
        ];

        $date = gmdate('Y-m-d');
        $file = self::$path . DIRECTORY_SEPARATOR . 'lilyweb-' . $date . '.log';
        $line = json_encode($entry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($line !== false) {
            @file_put_contents($file, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }

    public static function debug(string $m, array $c = []): void { self::log('debug', $m, $c); }
    public static function info(string $m, array $c = []): void { self::log('info', $m, $c); }
    public static function notice(string $m, array $c = []): void { self::log('notice', $m, $c); }
    public static function warning(string $m, array $c = []): void { self::log('warning', $m, $c); }
    public static function error(string $m, array $c = []): void { self::log('error', $m, $c); }
    public static function critical(string $m, array $c = []): void { self::log('critical', $m, $c); }
}

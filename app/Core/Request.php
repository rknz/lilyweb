<?php

declare(strict_types=1);

namespace Lilyweb\Core;

/**
 * HTTP Request wrapper (foundation-level). Abstraction over superglobals so
 * controllers never read $_GET/$_POST directly.
 */
final class Request
{
    private static ?string $method = null;
    private static ?string $path = null;
    private static ?string $uri = null;
    private static string $basePath = '';

    public static function bootstrap(): void
    {
        self::$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        self::$uri = $_SERVER['REQUEST_URI'] ?? '/';

        $rawPath = parse_url(self::$uri, PHP_URL_PATH) ?: '/';
        $rawPath = rawurldecode($rawPath);

        // Compute the app's base directory from SCRIPT_NAME so the router
        // receives clean paths (e.g. "/services") regardless of whether
        // the app is accessed via /public, root folder, or domain root.
        $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');
        if ($scriptDir === '/' || $scriptDir === '.') {
            $scriptDir = '';
        }

        // When the entry point is inside public/, strip the /public suffix
        // so the router sees clean paths like /services, /projects, etc.
        $publicDir = preg_replace('#/public$#i', '', $scriptDir);

        if ($publicDir !== '' && str_starts_with($rawPath, $publicDir)) {
            $rawPath = substr($rawPath, strlen($publicDir));
            self::$basePath = $publicDir;
        } elseif ($scriptDir !== '' && $scriptDir !== $publicDir && str_starts_with($rawPath, $scriptDir)) {
            $rawPath = substr($rawPath, strlen($scriptDir));
            self::$basePath = $scriptDir;
        } else {
            self::$basePath = '';
        }

        self::$path = '/' . ltrim($rawPath, '/');
    }

    public static function basePath(): string
    {
        return self::$basePath ?? '';
    }

    public static function method(): string
    {
        return self::$method ?? 'GET';
    }

    public static function path(): string
    {
        return self::$path ?? '/';
    }

    public static function uri(): string
    {
        return self::$uri ?? '/';
    }

    public static function isMethod(string $method): bool
    {
        return self::method() === strtoupper($method);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public static function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public static function all(): array
    {
        return $_REQUEST;
    }

    public static function input(string $key, mixed $default = null): mixed
    {
        $value = $_POST[$key] ?? $_GET[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }

    public static function header(string $name, ?string $default = null): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $_SERVER[$key] ?? $default;
    }

    public static function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public static function userAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    public static function server(string $key, mixed $default = null): mixed
    {
        return $_SERVER[$key] ?? $default;
    }

    public static function isSecure(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    }
}

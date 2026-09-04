<?php

declare(strict_types=1);

namespace Lilyweb\Core;

/**
 * Session foundation with a UNIQUE namespace isolated from Profixapp.
 *
 * Uses a distinct session name (config SESSION_NAME, default LILYWEB_SESS) and
 * its own file-based save path under storage/framework/sessions. This guarantees
 * the Lily website can never collide with Profixapp's PHPSESSID cookie/data.
 */
final class Session
{
    private static bool $started = false;

    public static function start(): void
    {
        if (self::$started || (session_status() === PHP_SESSION_ACTIVE)) {
            self::$started = true;
            return;
        }

        if (headers_sent()) {
            return;
        }

        $name = (string) Config::get('session.name', 'LILYWEB_SESS');
        $savePath = (string) Config::get('session.save_path', '');
        $lifetime = (int) Config::get('session.lifetime', 120);
        $secure = (bool) Config::get('session.secure', false);
        $httpOnly = (bool) Config::get('session.http_only', true);
        $sameSite = (string) Config::get('session.same_site', 'Lax');

        if ($savePath !== '' && is_dir(dirname($savePath))) {
            @mkdir($savePath, 0775, true);
            if (is_dir($savePath)) {
                session_save_path($savePath);
            }
        }

        session_name($name);
        session_set_cookie_params([
            'lifetime' => $lifetime * 60,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => $httpOnly,
            'samesite' => $sameSite,
        ]);

        session_start();
        self::$started = true;

        // Prevent session fixation on a new session.
        if (!isset($_SESSION['_created'])) {
            $_SESSION['_created'] = time();
            session_regenerate_id(true);
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION ?? []);
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key, mixed $default = null): mixed
    {
        return $_SESSION['_flash'][$key] ?? $default;
    }

    public static function flush(): void
    {
        unset($_SESSION['_flash']);
    }

    public static function id(): string
    {
        return session_id();
    }

    public static function regenerate(): void
    {
        if (self::$started) {
            session_regenerate_id(true);
        }
    }

    /**
     * Write and close the session cleanly. Safe if never started.
     */
    public static function save(): void
    {
        if (self::$started && session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
            self::$started = false;
        }
    }

    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        self::$started = false;
    }
}

<?php

/**
 * Session configuration — unique namespace isolated from Profixapp (PHPSESSID).
 */

use Lilyweb\Core\Env;

return [
    'name' => Env::get('SESSION_NAME', 'LILYWEB_SESS'),
    'lifetime' => (int) Env::get('SESSION_LIFETIME', '120'),
    'secure' => Env::bool('SESSION_SECURE', false) || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'http_only' => Env::bool('SESSION_HTTP_ONLY', true),
    'same_site' => Env::get('SESSION_SAME_SITE', 'Lax'),
    'use_cookies' => true,
    'use_only_cookies' => true,
    'use_strict_mode' => true,
    // File-based session files stored in our isolated storage namespace.
    'save_path' => rtrim(dirname(__DIR__), '/\\') . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'sessions',
];

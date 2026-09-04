<?php

/**
 * Local development router for PHP's built-in web server.
 *
 * Emulates Apache's .htaccess behaviour (route everything through
 * public/index.php) so `php -S ... -r router.php` matches production.
 *
 * Usage:
 *   php -S 127.0.0.1:8899 -t public router.php
 */

$docRoot = __DIR__ . '/public';
$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');

$file = realpath($docRoot . $path);

// Serve real files (assets, uploads) directly.
if ($file !== false && is_file($file)) {
    return false;
}

// Otherwise set SCRIPT_NAME to the front controller and dispatch to it,
// mirroring Apache's RewriteRule ^(.*)$ index.php.
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $docRoot . '/index.php';
require $docRoot . '/index.php';

<?php

/**
 * Local development router for PHP's built-in web server.
 *
 * Emulates Apache's .htaccess behaviour (route everything through index.php)
 * so running `php -S 127.0.0.1:8899 router.php` matches production 100%.
 */

$docRoot = __DIR__;
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = urldecode(parse_url($requestUri, PHP_URL_PATH) ?: '/');

$file = realpath($docRoot . $path);

// Serve real files (assets, uploads, css, js, images, fonts) directly
if ($file !== false && is_file($file) && !preg_match('/\.(php|sql|env|json|md|bat|ps1|bak|log|git)$/i', $file)) {
    $mimes = [
        'css'   => 'text/css; charset=UTF-8',
        'js'    => 'application/javascript; charset=UTF-8',
        'json'  => 'application/json; charset=UTF-8',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'webp'  => 'image/webp',
        'svg'   => 'image/svg+xml',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'eot'   => 'application/vnd.ms-fontobject',
        'pdf'   => 'application/pdf',
        'mp4'   => 'video/mp4',
    ];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mime = $mimes[$ext] ?? 'application/octet-stream';
    header("Content-Type: $mime");
    header("Content-Length: " . filesize($file));
    header("Cache-Control: public, max-age=3600");
    readfile($file);
    exit;
}

// Otherwise route through index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $docRoot . '/index.php';
require $docRoot . '/index.php';

<?php

declare(strict_types=1);

/**
 * Lily Interiors — Clean Root Front Controller.
 * Direct bootstrap with zero redirects and 100% clean URLs.
 */

$projectRoot = __DIR__;

// Fast Static Asset Passthrough Fallback (Handles /assets/, /uploads/, favicons, fonts)
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$cleanReqPath = str_replace(['../', '..\\'], '', rawurldecode($requestPath));

if (preg_match('/\.(css|js|png|jpe?g|webp|svg|gif|ico|woff2?|ttf|eot|json|webmanifest)$/i', $cleanReqPath)) {
    $candidates = [
        $projectRoot . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $cleanReqPath),
        $projectRoot . DIRECTORY_SEPARATOR . 'public' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $cleanReqPath),
        $projectRoot . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $cleanReqPath), DIRECTORY_SEPARATOR),
    ];

    foreach ($candidates as $candidate) {
        if (is_file($candidate)) {
            $ext = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));
            $mimes = [
                'css' => 'text/css; charset=utf-8',
                'js' => 'application/javascript; charset=utf-8',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'webp' => 'image/webp',
                'svg' => 'image/svg+xml',
                'gif' => 'image/gif',
                'ico' => 'image/x-icon',
                'webmanifest' => 'application/manifest+json',
                'json' => 'application/json',
                'woff2' => 'font/woff2',
                'woff' => 'font/woff',
                'ttf' => 'font/ttf',
            ];
            $contentType = $mimes[$ext] ?? 'application/octet-stream';
            header('Content-Type: ' . $contentType);
            header('Cache-Control: public, max-age=31536000');
            readfile($candidate);
            exit;
        }
    }
}

require $projectRoot . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'autoload.php';

use Lilyweb\Core\Bootstrap;

try {
    $bootstrap = new Bootstrap($projectRoot);
    $response = $bootstrap->handle();
    $response->send();
} catch (\Throwable $e) {
    http_response_code(500);
    echo '<!DOCTYPE html><html><head><title>Server Error</title><style>body{font-family:sans-serif;padding:2rem;background:#0d1117;color:#c9d1d9;}h1{color:#f85149;}.box{background:#161b22;padding:1.5rem;border-radius:8px;border:1px solid #30363d;}</style></head><body>';
    echo '<h1>Lily Interiors &mdash; Server Notice</h1>';
    echo '<div class="box"><p><strong>Diagnostic Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p></div></body></html>';
}

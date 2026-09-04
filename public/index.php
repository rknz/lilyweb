<?php

declare(strict_types=1);

/**
 * Lily Interiors — Public Entry Point / Front Controller.
 *
 * This file lives inside public/ (the DocumentRoot) and bootstraps the
 * application from the project root one level above.
 */

$projectRoot = dirname(__DIR__);

// Static asset passthrough for the PHP built-in server router.
if (PHP_SAPI === 'cli-server' && isset($_SERVER['SCRIPT_FILENAME'])) {
    $uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
    $candidate = __DIR__ . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $uri);

    if (is_file($candidate) && realpath($candidate) !== __FILE__) {
        return false;
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
    echo '<!DOCTYPE html><html><head><title>Server Configuration Check</title><style>body{font-family:sans-serif;padding:2rem;background:#0d1117;color:#c9d1d9;}h1{color:#f85149;}.box{background:#161b22;padding:1.5rem;border-radius:8px;border:1px solid #30363d;}code{color:#58a6ff;background:#21262d;padding:2px 6px;border-radius:4px;}pre{background:#0d1117;padding:1rem;border-radius:6px;overflow:auto;color:#8b949e;}</style></head><body>';
    echo '<h1>&#9888;&#65039; Lily Interiors &mdash; Server Notice</h1>';
    echo '<div class="box">';
    echo '<p><strong>Diagnostic Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>Location:</strong> <code>' . htmlspecialchars(basename($e->getFile())) . ':' . $e->getLine() . '</code></p>';
    echo '<p><strong>Action:</strong> Please check your <code>.env</code> database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD) or folder permissions on <code>storage/</code>.</p>';
    echo '<details><summary style="cursor:pointer;color:#58a6ff;margin-top:1rem;">View Technical Stack Trace</summary><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre></details>';
    echo '</div></body></html>';
}

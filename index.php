<?php

declare(strict_types=1);

/**
 * Lily Interiors — Clean Root Front Controller.
 * Direct bootstrap with zero redirects and 100% clean URLs.
 */

$projectRoot = __DIR__;

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

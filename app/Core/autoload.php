<?php

declare(strict_types=1);

/**
 * PSR-4 autoloader for the Lilyweb\ namespace (maps to app/).
 * Registered globally so no runtime dependency on composer is required for
 * production hosting (foundation can run on bare PHP).
 */

spl_autoload_register(static function (string $class): void {
    $prefix = 'Lilyweb\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    // If class starts with App\, strip it so Lilyweb\App\ maps directly to app/
    if (str_starts_with($relative, 'App\\')) {
        $relative = substr($relative, 4);
    }
    // Project root is two levels up from app/Core/.
    $file = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR
        . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

require_once __DIR__ . DIRECTORY_SEPARATOR . 'helpers.php';

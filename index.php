<?php

declare(strict_types=1);

/**
 * Lily Interiors — Root Entry Point (Safety Redirect).
 *
 * If your web server's DocumentRoot points to this directory (project root)
 * instead of the public/ subdirectory, this file redirects to the correct
 * entry point. Set your DocumentRoot to public/ for best results.
 */

$uri = $_SERVER['REQUEST_URI'] ?? '/';

// If already inside public/, serve normally
if (strpos($uri, '/public/') === 0 || $uri === '/public') {
    // Shouldn't normally reach here if DocumentRoot is set to public/
    // but handle gracefully
    require __DIR__ . '/public/index.php';
    return;
}

// Redirect to public/ subdirectory
header('Location: /public' . $uri);
exit;

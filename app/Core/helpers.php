<?php

declare(strict_types=1);

use Lilyweb\Core\Request;

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $base = Request::basePath();
        $cleanPath = '/' . ltrim($path, '/');
        return ($base === '' || $base === '/') ? $cleanPath : $base . $cleanPath;
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $base = Request::basePath();
        if ($path === '' || $path === '/') {
            return ($base === '' || $base === '/') ? '/' : $base . '/';
        }
        $cleanPath = '/' . ltrim($path, '/');
        return ($base === '' || $base === '/') ? $cleanPath : $base . $cleanPath;
    }
}

// Global Aliases for View Templates
if (!class_exists('Security')) class_alias(\Lilyweb\Core\Security::class, 'Security');
if (!class_exists('View')) class_alias(\Lilyweb\Core\View::class, 'View');
if (!class_exists('Auth')) class_alias(\Lilyweb\Core\Auth::class, 'Auth');
if (!class_exists('Lang')) class_alias(\Lilyweb\Core\Lang::class, 'Lang');
if (!class_exists('Session')) class_alias(\Lilyweb\Core\Session::class, 'Session');


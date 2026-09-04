<?php

/**
 * Application configuration (non-secret defaults).
 * Environment-specific values come from .env via Lilyweb\Core\Env.
 */

use Lilyweb\Core\Env;

return [
    'name' => Env::get('APP_NAME', 'Lily Interiors'),
    'env' => Env::get('APP_ENV', 'production'),
    'debug' => Env::bool('APP_DEBUG', false),
    'url' => Env::get('APP_URL', 'http://localhost'),
    'timezone' => Env::get('APP_TIMEZONE', 'UTC'),
    'key' => Env::get('APP_KEY', ''),
    'locale' => Env::get('APP_LOCALE', 'en'),
    'locales' => ['en', 'bn'],
    'default_locale' => 'en',
];

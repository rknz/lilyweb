<?php

/**
 * Security configuration (foundation-level). Detailed hardening occurs in later phases.
 */

use Lilyweb\Core\Env;

return [
    'app_key' => Env::get('APP_KEY', ''),
    'password_algo' => PASSWORD_DEFAULT,
    'session' => [
        'regenerate_on_login' => true,
    ],
    'security_headers' => [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'SAMEORIGIN',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'X-XSS-Protection' => '1; mode=block',
        'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=()',
        // HSTS: enable when HTTPS is confirmed in production
        // 'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
    ],
    'rate_limit' => [
        'login' => ['max_attempts' => 5, 'decay_minutes' => 15],
        'contact' => ['max_attempts' => 10, 'decay_minutes' => 60],
    ],
];

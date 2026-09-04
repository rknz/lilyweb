<?php

/**
 * Database configuration.
 *
 * The dedicated database (lily_web) plus the lilyweb_ table prefix enforce full
 * isolation from the existing lily_app / Profixapp database. All migrations must
 * use ENGINE=InnoDB and utf8mb4_unicode_ci (see docs/DATABASE.md).
 */

use Lilyweb\Core\Env;

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => Env::get('DB_HOST', '127.0.0.1'),
            'port' => (int) Env::get('DB_PORT', '3306'),
            'database' => Env::get('DB_DATABASE', 'lily_web'),
            'username' => Env::get('DB_USERNAME', 'root'),
            'password' => Env::get('DB_PASSWORD', ''),
            'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
            'collation' => Env::get('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => Env::get('DB_PREFIX', 'lilyweb_'),
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ],
        ],
    ],
];

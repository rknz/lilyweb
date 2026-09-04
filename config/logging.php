<?php

/**
 * Logging configuration.
 */

use Lilyweb\Core\Env;

return [
    'channel' => Env::get('LOG_CHANNEL', 'file'),
    'level' => Env::get('LOG_LEVEL', 'warning'),
    'path' => rtrim(dirname(__DIR__), '/\\') . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs',
    'max_files' => 30,
];

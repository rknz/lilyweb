<?php

declare(strict_types=1);

namespace Lilyweb\Core\Exceptions;

/**
 * HttpException carries an HTTP status code and optional safe message.
 * Used by the router / controllers to signal 404, 405, 403, etc.
 */
class HttpException extends \RuntimeException
{
    private int $status;

    public function __construct(int $status = 500, string $message = 'Server Error', ?\Throwable $previous = null)
    {
        $this->status = $status;
        $text = [
            400 => 'Bad Request',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            419 => 'Page Expired',
            422 => 'Unprocessable Entity',
            429 => 'Too Many Requests',
            500 => 'Server Error',
            503 => 'Service Unavailable',
        ];

        parent::__construct($message !== '' ? $message : ($text[$status] ?? 'Server Error'), $status, $previous);
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}

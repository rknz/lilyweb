<?php

declare(strict_types=1);

namespace Lilyweb\Core;

/**
 * HTTP Response wrapper.
 */
final class Response
{
    private int $status;
    private array $headers = [];
    private string $content = '';

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function status(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function contentType(string $type, string $charset = 'utf-8'): self
    {
        $this->header('Content-Type', $type . ($charset ? '; charset=' . $charset : ''));
        return $this;
    }

    public static function json(array|object $data, int $status = 200): self
    {
        $res = new self();
        $res->content = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}';
        $res->status = $status;
        return $res->contentType('application/json');
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
        echo $this->content;
    }

    public static function redirect(string $url, int $status = 302): self
    {
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://') && !str_starts_with($url, '//')) {
            $base = Request::basePath();
            if ($base !== '' && $base !== '/') {
                $url = $base . '/' . ltrim($url, '/');
            }
        }
        return (new self('', $status))->header('Location', $url);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}

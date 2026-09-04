<?php

declare(strict_types=1);

namespace Lilyweb\Core;

/**
 * View / layout rendering foundation.
 *
 * Views are PHP templates under app/Views. Dot notation maps to path:
 *   'pages.home'      -> app/Views/pages/home.php
 *   'layouts.app'     -> app/Views/layouts/app.php
 *   'errors.404'      -> app/Views/errors/404.php
 *
 * Base layout/component architecture: a view can be composed inside a layout via
 * startSection()/yieldSection() or by rendering the layout template with the
 * view's rendered content passed as $content.
 */
final class View
{
    private static ?string $path = null;
    private static bool $ready = false;

    private string $view;
    private array $data = [];

    public function __construct(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    public static function init(string $viewPath): void
    {
        self::$path = rtrim($viewPath, '/\\');
        self::$ready = true;
    }

    public static function ready(): bool
    {
        return self::$ready;
    }

    public static function make(string $view, array $data = []): self
    {
        return new self($view, $data);
    }

    public function with(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function resolvePath(?string $view = null): string
    {
        $view = $view ?? $this->view;
        $file = str_replace('.', DIRECTORY_SEPARATOR, $view) . '.php';
        return (self::$path ? self::$path . DIRECTORY_SEPARATOR : '') . $file;
    }

    public function render(): string
    {
        $file = $this->resolvePath();
        if (!is_file($file)) {
            throw new \RuntimeException('View not found: ' . $this->view);
        }

        return $this->capture($file, $this->data);
    }

    /**
     * Render a view's content into an output buffer (used by layouts and partials).
     */
    public static function renderPartial(string $view, array $data = []): string
    {
        $file = (new self($view))->resolvePath();
        if (!is_file($file)) {
            throw new \RuntimeException('View not found: ' . $view);
        }
        return (new self($view))->capture($file, $data);
    }

    /**
     * Output-escape helper for templates: e($title).
     */
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    private function capture(string $__file, array $__data): string
    {
        extract($__data, EXTR_SKIP);
        unset($__data);
        ob_start();
        try {
            include $__file;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
        return (string) ob_get_clean();
    }
}

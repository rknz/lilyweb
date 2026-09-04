<?php

declare(strict_types=1);

namespace Lilyweb\Core;

use Lilyweb\Core\Exceptions\HttpException;

/**
 * Global error and exception handler.
 *
 * In debug mode exceptions render a detailed page; in production they render a
 * generic page and are logged. Never leak environment/secrets in production.
 */
final class ErrorHandler
{
    private static bool $registered = false;

    public static function register(): void
    {
        if (self::$registered) {
            return;
        }
        self::$registered = true;

        $debug = Config::get('app.debug', false);

        error_reporting(E_ALL);
        ini_set('display_errors', $debug ? '1' : '0');

        set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
            if (!(error_reporting() & $severity)) {
                return false; // suppressed with @
            }
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        set_exception_handler(static function (\Throwable $e): void {
            self::handleException($e);
        });

        register_shutdown_function(static function (): void {
            $error = error_get_last();
            if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
                $exception = new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
                Logger::critical('Fatal error', ['message' => $error['message'], 'file' => $error['file'], 'line' => $error['line']]);
                self::renderErrorPage(500, null, Config::get('app.debug', false));
            }
        });
    }

    public static function handleException(\Throwable $e): void
    {
        $debug = Config::get('app.debug', false);

        if ($e instanceof HttpException) {
            $status = $e->getStatus();
            Logger::warning('HTTP ' . $status, ['url' => Request::path() ?? '', 'message' => $e->getMessage()]);
            self::renderErrorPage($status, $e, $debug);
            return;
        }

        Logger::error('Uncaught exception', [
            'message' => $e->getMessage(),
            'class' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        self::renderErrorPage(500, $e, $debug);
    }

    private static function renderErrorPage(int $status, ?\Throwable $e, bool $debug): void
    {
        if (!headers_sent()) {
            http_response_code($status);
            header('Content-Type: text/html; charset=utf-8');
        }

        $useView = !headers_sent() && class_exists(View::class) && View::ready();

        if ($useView) {
            $viewName = 'errors.' . $status;
            $viewFile = (new View($viewName))->resolvePath();
            if (!is_file($viewFile)) {
                $viewName = 'errors.default';
            }
            try {
                $view = new View($viewName);
                $view->with(['status' => $status, 'debug' => $debug, 'exception' => $e]);
                echo $view->render();
                exit;
            } catch (\Throwable $viewError) {
                // fall through to plain output
            }
        }

        if ($debug && $e !== null) {
            echo '<h1>Error ' . $status . '</h1>';
            echo '<p><strong>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</strong></p>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8') . '</pre>';
        } else {
            $messages = [404 => 'Page not found', 403 => 'Forbidden', 405 => 'Method not allowed', 500 => 'Internal server error'];
            echo '<h1>' . $status . '</h1><p>' . ($messages[$status] ?? 'Server error') . '</p>';
        }
        exit;
    }
}

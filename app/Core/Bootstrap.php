<?php

declare(strict_types=1);

namespace Lilyweb\Core;

/**
 * Application bootstrap.
 *
 * Sequences the foundation lifecycle in the correct order:
 *   1. load environment + config
 *   2. register autoloader + error handler
 *   3. request + session + logging
 *   4. security headers
 *   5. routes
 *   6. dispatch
 *
 * Returns an HTTP Response.
 */
final class Bootstrap
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/\\');
    }

    public function handle(): Response
    {
        $this->registerAutoloader();
        Env::load($this->basePath);
        Config::load($this->basePath . DIRECTORY_SEPARATOR . 'config');

        date_default_timezone_set((string) Config::get('app.timezone', 'UTC'));

        $debug = (bool) Config::get('app.debug', false);
        ini_set('display_errors', $debug ? '1' : '0');

        ErrorHandler::register();

        Logger::init(
            (string) Config::get('logging.path', $this->basePath . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs'),
            (string) Config::get('logging.level', 'debug')
        );

        View::init($this->basePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views');

        Request::bootstrap();
        Session::start();
        Security::sendSecurityHeaders();

        $routes = $this->basePath . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'web.php';
        if (is_file($routes)) {
            require $routes;
        }

        try {
            $result = Router::dispatch(Request::method(), Request::path());

            if ($result instanceof Response) {
                return $result;
            }
            if (is_string($result) || is_numeric($result)) {
                return new Response((string) $result);
            }
            return new Response('');
        } catch (Exceptions\HttpException $e) {
            ErrorHandler::handleException($e);
        } catch (\Throwable $e) {
            ErrorHandler::handleException($e);
        }

        // Unreachable in practice (handler exits), kept for static analysis.
        return new Response('', 500);
    }

    private function registerAutoloader(): void
    {
        require $this->basePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'autoload.php';
    }
}

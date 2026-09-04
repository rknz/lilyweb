<?php

declare(strict_types=1);

namespace Lilyweb\Core;

use Lilyweb\Core\Exceptions\HttpException;

/**
 * Routing foundation.
 *
 * A lightweight front-controller router. Routes are registered by the route
 * collector (routes/web.php). Each route maps an HTTP method + URI pattern to a
 * [ControllerClass, method] pair (or a closure). Server-rendered HTML is the
 * source of truth; clean URLs are achieved via public/.htaccess rewrites.
 *
 * Pattern syntax supports named parameters: '/projects/{slug}'
 * Optional params: '/projects/{slug?}' (not required for this phase).
 */
final class Router
{
    /** @var array<int,array{method:string,pattern:string,handler:mixed,params:array<mixed>}> */
    private static array $routes = [];

    public static function add(string $method, string $pattern, mixed $handler): void
    {
        self::$routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler,
            'params' => [],
        ];
    }

    public static function get(string $pattern, mixed $handler): void { self::add('GET', $pattern, $handler); }
    public static function post(string $pattern, mixed $handler): void { self::add('POST', $pattern, $handler); }
    public static function any(string $pattern, mixed $handler): void { self::add('ANY', $pattern, $handler); }

    /**
     * Dispatch the current request. Resolves the matched controller/closure and
     * returns a Response (or the string/number the handler produced).
     */
    public static function dispatch(string $method, string $path): mixed
    {
        $upperMethod = strtoupper($method);
        foreach (self::$routes as $route) {
            if ($route['method'] !== 'ANY' && $route['method'] !== $upperMethod && !($route['method'] === 'GET' && $upperMethod === 'HEAD')) {
                continue;
            }

            $match = self::matchPattern($route['pattern'], $path);
            if ($match === null) {
                continue;
            }

            $params = $match;
            $handler = $route['handler'];

            if ($handler instanceof \Closure) {
                return $handler($params);
            }

            if (is_array($handler) && count($handler) === 2) {
                [$class, $action] = $handler;
                if (!class_exists($class)) {
                    throw new \RuntimeException('Controller not found: ' . $class);
                }
                $instance = new $class();
                if (!method_exists($instance, $action)) {
                    throw new \RuntimeException('Action not found: ' . $class . '@' . $action);
                }
                return $instance->{$action}($params);
            }

            throw new \RuntimeException('Invalid route handler for ' . $route['pattern']);
        }

        throw new HttpException(404, 'Page not found');
    }

    /**
     * Match a pattern against a path. Returns params array or null.
     */
    public static function matchPattern(string $pattern, string $path): ?array
    {
        $pattern = rtrim($pattern, '/') ?: '/';
        $path = rtrim($path, '/') ?: '/';

        $patternSegments = explode('/', $pattern);
        $pathSegments = explode('/', $path);

        if (count($patternSegments) !== count($pathSegments)) {
            return null;
        }

        $params = [];
        foreach ($patternSegments as $i => $segment) {
            $ps = $pathSegments[$i] ?? null;
            if ($segment !== $ps) {
                // Named parameter segment: {name}
                if (preg_match('/^\{(\w+)\}$/', $segment, $m)) {
                    $params[$m[1]] = $ps;
                    continue;
                }
                return null;
            }
        }

        return $params;
    }
}

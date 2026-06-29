<?php
class Router
{
    private static array $routes = [];

    public static function get(string $path, array $handler, ...$middleware): void
    {
        self::add('GET', $path, $handler, $middleware);
    }

    public static function post(string $path, array $handler, ...$middleware): void
    {
        self::add('POST', $path, $handler, $middleware);
    }

    public static function put(string $path, array $handler, ...$middleware): void
    {
        self::add('PUT', $path, $handler, $middleware);
    }

    public static function delete(string $path, array $handler, ...$middleware): void
    {
        self::add('DELETE', $path, $handler, $middleware);
    }

    private static function add(string $method, string $path, array $handler, array $middleware): void
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        self::$routes[] = [
            'method'     => $method,
            'pattern'    => $pattern,
            'handler'    => $handler,
            'middleware' => $middleware,
        ];
    }

    public static function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) continue;
            if (!preg_match($route['pattern'], $uri, $matches)) continue;

            foreach ($route['middleware'] as $mw) {
                $mwInstance = new $mw();
                $mwInstance->handle();
            }

            [$class, $action] = $route['handler'];
            $controller = new $class();
            $params = array_values(array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));
            $controller->$action(...$params);
            return;
        }

        http_response_code(404);
        require_once __DIR__ . '/../app/views/errors/404.php';
    }
}

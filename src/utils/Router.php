<?php
namespace App\Utils;

class Router
{
    protected $routes = [];

    public function post($path, $controller, $method)
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function get($path, $controller, $method)
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch($requestMethod, $requestPath)
    {
        $routes = $this->routes[$requestMethod] ?? [];
        foreach ($routes as $path => $route) {
            if ($this->matchPath($path, $requestPath)) {
                return $this->executeRoute($route);
            }
        }
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Route not found']);
    }

    protected function matchPath($pattern, $requestPath)
    {
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = preg_replace('/\{[a-z_]+\}/', '([a-z0-9-_]+)', $pattern);
        return preg_match('/^' . $pattern . '$/', $requestPath);
    }

    protected function executeRoute($route)
    {
        $controllerClass = $route['controller'];
        $method = $route['method'];
        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Controller not found']);
            return;
        }
        $controller = new $controllerClass();
        $controller->$method();
    }
}

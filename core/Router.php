<?php

class Router {
    private $routes = [];

    public function add($method, $path, $callback) {
        $method = strtoupper($method);
        $pattern = preg_replace('#\{([\w]+)\}#', '([^/]+)', $path); // Convert {id} to regex
        $pattern = '#^' . $pattern . '$#';
        $this->routes[$method][$pattern] = ['callback' => $callback, 'params' => []];
    }

    public function resolve($uri, $method) {
        $path = parse_url($uri, PHP_URL_PATH);

        // If your project is in /phpapi, remove it
        $basePath = '/phpapi';
        if (str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }
        $basePath = '/hcse-api';
        if (str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }


        $method = strtoupper($method);



        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }


        foreach ($this->routes[$method] as $pattern => $route) {
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove full match
                call_user_func_array($route['callback'], $matches);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}
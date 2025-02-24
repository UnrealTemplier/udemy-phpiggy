<?php

declare(strict_types=1);

namespace Framework;

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function add(string $method, string $path, array $controller): void
    {
        $path = $this->normalizePath($path);
        $regexPath = preg_replace("#{[^/]+}#", "([^/]+)", $path);

        $this->routes[] = [
            "path" => $path,
            "regexPath" => $regexPath,
            "method" => strtoupper($method),
            "controller" => $controller,
            "middlewares" => [],
        ];
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path);
        $path = trim($path, "/");
        $path = "/{$path}/";
        return preg_replace("#/{2,}#", "/", $path);
    }

    public function dispatch(string $path, string $method, Container $container = null): void
    {
        $path = $this->normalizePath($path);
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if (
                !preg_match("#^{$route["regexPath"]}$#", $path, $paramValues) ||
                $method !== $route["method"]
            ) {
                continue;
            }

            array_shift($paramValues);
            preg_match_all("#{([^/]+)}#", $route["path"], $paramKeys);
            $paramKeys = $paramKeys[1];
            $params = array_combine($paramKeys, $paramValues);

            [$class, $function] = $route["controller"];
            $controllerInstance = $container ?
                $container->resolve($class) :
                new $class();

            $action = fn() => $controllerInstance->{$function}($params);

            $allMiddlewares = [...$route["middlewares"], ...$this->middlewares];

            foreach ($allMiddlewares as $middleware) {
                $middlewareInstance = $container ?
                    $container->resolve($middleware) :
                    new $middleware();
                $action = fn() => $middlewareInstance->process($action);
            }

            $action();

            return;
        }
    }

    public function addMiddleware(string $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function addRouteMiddleware(string $middleware): void
    {
        $lastRouteKey = array_key_last($this->routes);
        $this->routes[$lastRouteKey]["middlewares"][] = $middleware;
    }
}

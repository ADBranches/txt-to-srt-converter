<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Http;

final class Router
{
    /** @var array<string, callable(Request): Response> */
    private array $routes = [];

    /** @param callable(Request): Response $handler */
    public function add(string $method, string $path, callable $handler): void
    {
        $this->routes[strtoupper($method) . ' ' . $path] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        $handler = $this->routes[$request->method . ' ' . $request->path] ?? null;
        if ($handler === null) {
            throw new HttpException(404, 'The requested page was not found.');
        }
        return $handler($request);
    }
}

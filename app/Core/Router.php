<?php

class Router {
  private array $routes = ['GET'=>[], 'POST'=>[]];

  public function get(string $path, callable $handler): void {
    $this->routes['GET'][$path] = $handler;
  }
  public function post(string $path, callable $handler): void {
    $this->routes['POST'][$path] = $handler;
  }

  public function dispatch(): void {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

    $base = App::baseUrl();
    if ($base !== '' && str_starts_with($uri, $base)) {
      $uri = substr($uri, strlen($base));
      if ($uri === '') $uri = '/';
    }

    $handler = $this->routes[$method][$uri] ?? null;

    if (!$handler) {
      http_response_code(404);
      echo "404 Not Found";
      return;
    }

    $handler();
  }
}

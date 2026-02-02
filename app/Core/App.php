<?php

class App {
  public static function cfg(string $key, $default = null) {
    static $cfg = null;
    if ($cfg === null) $cfg = require __DIR__ . '/../../config/app.php';
    return $cfg[$key] ?? $default;
  }

  public static function baseUrl(): string {
    return rtrim((string)self::cfg('base_url', ''), '/');
  }

  public static function url(string $path): string {
    $base = self::baseUrl();
    if ($path === '/') return $base . '/';
    return $base . $path;
  }

  public static function redirect(string $path): void {
    header("Location: " . self::url($path));
    exit;
  }
}

class View {
  public static function render(string $path, array $data = []): void {
    extract($data);

    // helpers available in views
    if (!function_exists('e')) {
      function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
    }
    if (!function_exists('asset')) {
      function asset(string $p): string { return App::url($p); }
    }
    if (!function_exists('csrf_field')) {
      function csrf_field(): string { return '<input type="hidden" name="_csrf" value="'.e(Csrf::token()).'">'; }
    }
    if (!function_exists('cart_count')) {
      function cart_count(): int {
        $cart = Session::get('cart', []);
        if (!is_array($cart)) return 0;
        $sum = 0;
        foreach ($cart as $q) { $n = (int)$q; if ($n>0) $sum += $n; }
        return $sum;
      }
    }

    $full = __DIR__ . '/../Views/' . $path . '.php';
    if (!file_exists($full)) {
      die("View not found: " . $path);
    }

    require __DIR__ . '/../Views/layouts/header.php';
    require $full;
    require __DIR__ . '/../Views/layouts/footer.php';
  }
}

<?php

class Csrf {
  public static function token(): string {
    Session::start();
    $t = Session::get('_csrf');
    if (!$t) {
      $t = bin2hex(random_bytes(16));
      Session::set('_csrf', $t);
    }
    return (string)$t;
  }

  public static function check(): void {
    $posted = $_POST['_csrf'] ?? '';
    if (!$posted || $posted !== Session::get('_csrf')) {
      http_response_code(419);
      die("CSRF token mismatch.");
    }
  }
}

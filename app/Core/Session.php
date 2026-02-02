<?php

class Session {
  public static function start(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
  }

  public static function get(string $key, $default = null) {
    self::start();
    return $_SESSION[$key] ?? $default;
  }

  public static function set(string $key, $value): void {
    self::start();
    $_SESSION[$key] = $value;
  }

  public static function forget(string $key): void {
    self::start();
    unset($_SESSION[$key]);
  }

  public static function destroy(): void {
    self::start();
    session_unset();
    session_destroy();
  }

  // Flash
  public static function flashSet(string $key, string $msg): void {
    self::start();
    $_SESSION["flash_" . $key] = $msg;
  }

  public static function flashGet(string $key): string {
    self::start();
    $k = "flash_" . $key;
    if (!isset($_SESSION[$k])) return "";
    $msg = (string)$_SESSION[$k];
    unset($_SESSION[$k]);
    return $msg;
  }
}

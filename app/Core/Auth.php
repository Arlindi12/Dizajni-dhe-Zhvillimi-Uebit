<?php

class Auth {
  public static function check(): bool {
    return (int)Session::get('user_id', 0) > 0;
  }

  public static function id(): int {
    return (int)Session::get('user_id', 0);
  }

  public static function name(): string {
    return (string)Session::get('user_name', 'User');
  }

  public static function role(): string {
    return (string)Session::get('user_role', 'user');
  }

  public static function isAdmin(): bool {
    return self::role() === 'admin';
  }

  public static function requireLogin(): void {
    if (!self::check()) {
      App::redirect('/login');
    }
  }

  public static function requireAdmin(): void {
    self::requireLogin();
    if (!self::isAdmin()) {
      http_response_code(403);
      die("Access denied (admin only).");
    }
  }

  public static function login(array $user): void {
    Session::set('user_id', (int)$user['id']);
    Session::set('user_name', (string)$user['name']);
    Session::set('user_role', (string)($user['role'] ?? 'user'));
  }

  public static function logout(): void {
    Session::destroy();
  }
}

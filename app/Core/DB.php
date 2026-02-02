<?php

class DB {
  private static ?mysqli $conn = null;

  public static function conn(): mysqli {
    if (self::$conn) return self::$conn;

    $cfg = require __DIR__ . '/../../config/database.php';

    $c = new mysqli($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name']);
    if ($c->connect_error) {
      die("DB error: " . $c->connect_error);
    }
    $c->set_charset($cfg['charset'] ?? 'utf8mb4');

    self::$conn = $c;
    return $c;
  }
}

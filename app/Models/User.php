<?php

class User {
  public static function findByEmail(string $email): ?array {
    $conn = DB::conn();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = ($res && $res->num_rows>0) ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row ?: null;
  }

  public static function create(string $name, string $email, string $password): bool {
    $conn = DB::conn();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
    $stmt->bind_param("sss", $name, $email, $hash);
    $ok = $stmt->execute();
    $stmt->close();
    return (bool)$ok;
  }

  public static function countAdmins(): int {
    $conn = DB::conn();
    $r = $conn->query("SELECT COUNT(*) c FROM users WHERE role='admin'")->fetch_assoc();
    return (int)($r['c'] ?? 0);
  }

  public static function paginate(string $q, int $page, int $perPage): array {
    $conn = DB::conn();
    $offset = ($page-1)*$perPage;

    if ($q !== "") {
      $like = "%{$q}%";
      $stmtC = $conn->prepare("SELECT COUNT(*) c FROM users WHERE name LIKE ? OR email LIKE ?");
      $stmtC->bind_param("ss", $like, $like);
      $stmtC->execute();
      $total = (int)$stmtC->get_result()->fetch_assoc()['c'];
      $stmtC->close();

      $stmt = $conn->prepare("SELECT id,name,email,role,created_at FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?");
      $stmt->bind_param("ssii", $like, $like, $perPage, $offset);
    } else {
      $total = (int)$conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
      $stmt = $conn->prepare("SELECT id,name,email,role,created_at FROM users ORDER BY id DESC LIMIT ? OFFSET ?");
      $stmt->bind_param("ii", $perPage, $offset);
    }

    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $stmt->close();

    return [$rows, $total];
  }

  public static function find(int $id): ?array {
    $conn = DB::conn();
    $stmt = $conn->prepare("SELECT id,name,email,role FROM users WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row ?: null;
  }

  public static function update(int $id, string $name, string $email, string $role): bool {
    $conn = DB::conn();
    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $role, $id);
    $ok = $stmt->execute();
    $stmt->close();
    return (bool)$ok;
  }

  public static function setPassword(int $id, string $newPass): bool {
    $conn = DB::conn();
    $hash = password_hash($newPass, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param("si", $hash, $id);
    $ok = $stmt->execute();
    $stmt->close();
    return (bool)$ok;
  }

  public static function delete(int $id): bool {
    $conn = DB::conn();
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return (bool)$ok;
  }
}

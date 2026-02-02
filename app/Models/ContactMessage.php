<?php

class ContactMessage {
  public static function create(string $name, string $email, string $subject, string $message): bool {
    $conn = DB::conn();
    $stmt = $conn->prepare("INSERT INTO contacts (name,email,subject,message) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    $ok = $stmt->execute();
    $stmt->close();
    return (bool)$ok;
  }

  public static function paginate(string $q, int $page, int $perPage): array {
    $conn = DB::conn();
    $offset = ($page-1)*$perPage;

    if ($q !== "") {
      $like = "%{$q}%";
      $stmtC = $conn->prepare("SELECT COUNT(*) c FROM contacts WHERE name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?");
      $stmtC->bind_param("ssss", $like, $like, $like, $like);
      $stmtC->execute();
      $total = (int)$stmtC->get_result()->fetch_assoc()['c'];
      $stmtC->close();

      $stmt = $conn->prepare("SELECT id,name,email,subject,created_at FROM contacts
        WHERE name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?
        ORDER BY id DESC LIMIT ? OFFSET ?");
      $stmt->bind_param("ssssii", $like, $like, $like, $like, $perPage, $offset);
    } else {
      $total = (int)$conn->query("SELECT COUNT(*) c FROM contacts")->fetch_assoc()['c'];
      $stmt = $conn->prepare("SELECT id,name,email,subject,created_at FROM contacts ORDER BY id DESC LIMIT ? OFFSET ?");
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
    $stmt = $conn->prepare("SELECT id,name,email,subject,message,created_at FROM contacts WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row ?: null;
  }

  public static function delete(int $id): bool {
    $conn = DB::conn();
    $stmt = $conn->prepare("DELETE FROM contacts WHERE id=?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return (bool)$ok;
  }
}

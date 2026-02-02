<?php

class Order {
  public static function create(int $userId, string $fullName, string $phone, string $address, string $payment, float $total, array $items): int {
    $conn = DB::conn();

    $stmt = $conn->prepare("INSERT INTO orders (user_id, full_name, phone, address, payment_method, status, total)
                            VALUES (?,?,?,?,?,'pending',?)");
    $stmt->bind_param("issssd", $userId, $fullName, $phone, $address, $payment, $total);
    $stmt->execute();
    $orderId = (int)$stmt->insert_id;
    $stmt->close();

    if ($orderId <= 0) return 0;

    $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, book_id, title, price, qty, subtotal)
                             VALUES (?,?,?,?,?,?)");

    foreach ($items as $it) {
      $oid = $orderId;
      $bid = (int)$it["book_id"];
      $title = (string)$it["title"];
      $price = (float)$it["price"];
      $qty = (int)$it["qty"];
      $sub = (float)$it["subtotal"];
      $stmt2->bind_param("iisdid", $oid, $bid, $title, $price, $qty, $sub);
      $stmt2->execute();
    }
    $stmt2->close();

    return $orderId;
  }

  public static function myOrders(int $userId): array {
    $conn = DB::conn();
    $stmt = $conn->prepare("SELECT id,status,total,created_at FROM orders WHERE user_id=? ORDER BY id DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $stmt->close();
    return $rows;
  }

  public static function myOrder(int $userId, int $orderId): ?array {
    $conn = DB::conn();
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=? LIMIT 1");
    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row ?: null;
  }

  public static function items(int $orderId): array {
    $conn = DB::conn();
    $stmt = $conn->prepare("SELECT title,price,qty,subtotal FROM order_items WHERE order_id=? ORDER BY id ASC");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $stmt->close();
    return $rows;
  }

  // Admin
  public static function counts(): array {
    $c = DB::conn();
    $users = (int)$c->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
    $books = (int)$c->query("SELECT COUNT(*) c FROM books")->fetch_assoc()['c'];
    $contacts = (int)$c->query("SELECT COUNT(*) c FROM contacts")->fetch_assoc()['c'];
    $orders = (int)$c->query("SELECT COUNT(*) c FROM orders")->fetch_assoc()['c'];
    $pending = (int)$c->query("SELECT COUNT(*) c FROM orders WHERE status='pending'")->fetch_assoc()['c'];
    return compact('users','books','contacts','orders','pending');
  }

  public static function paginateAdmin(string $q, string $status, int $page, int $perPage): array {
    $conn = DB::conn();
    $offset = ($page-1)*$perPage;

    $where = [];
    $params = [];
    $types = "";

    if ($q !== "") {
      $where[] = "(o.id = ? OR o.user_id = ? OR o.full_name LIKE ? OR o.phone LIKE ? OR o.address LIKE ?)";
      $idTry = (int)$q;
      $params[] = $idTry;
      $params[] = $idTry;
      $like = "%{$q}%";
      $params[] = $like;
      $params[] = $like;
      $params[] = $like;
      $types .= "iisss";
    }

    if ($status !== "") {
      $where[] = "o.status = ?";
      $params[] = $status;
      $types .= "s";
    }

    $whereSql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

    $sqlCount = "SELECT COUNT(*) c FROM orders o $whereSql";
    $stmtC = $conn->prepare($sqlCount);
    if ($types !== "") $stmtC->bind_param($types, ...$params);
    $stmtC->execute();
    $total = (int)($stmtC->get_result()->fetch_assoc()["c"] ?? 0);
    $stmtC->close();

    $sql = "SELECT o.id,o.user_id,o.full_name,o.phone,o.payment_method,o.status,o.total,o.created_at,u.email AS user_email
            FROM orders o
            LEFT JOIN users u ON u.id = o.user_id
            $whereSql
            ORDER BY o.id DESC
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);

    if ($types !== "") {
      $types2 = $types . "ii";
      $params2 = array_merge($params, [$perPage, $offset]);
      $stmt->bind_param($types2, ...$params2);
    } else {
      $stmt->bind_param("ii", $perPage, $offset);
    }

    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $stmt->close();

    return [$rows, $total];
  }

  public static function findAdmin(int $id): ?array {
    $conn = DB::conn();
    $stmt = $conn->prepare("SELECT o.*, u.email AS user_email
                            FROM orders o
                            LEFT JOIN users u ON u.id=o.user_id
                            WHERE o.id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row ?: null;
  }

  public static function itemsAdmin(int $id): array {
    $conn = DB::conn();
    $stmt = $conn->prepare("SELECT title,price,qty,subtotal FROM order_items WHERE order_id=? ORDER BY id ASC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $stmt->close();
    return $rows;
  }

  public static function updateStatus(int $id, string $status): bool {
    $conn = DB::conn();
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $ok = $stmt->execute();
    $stmt->close();
    return (bool)$ok;
  }
}

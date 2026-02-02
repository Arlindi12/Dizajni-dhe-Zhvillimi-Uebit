<?php

class Book {
  public static function latest(int $limit): array {
    $conn = DB::conn();
    $stmt = $conn->prepare("SELECT id,title,slug,image,category,author,price,description FROM books ORDER BY id DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $stmt->close();
    return $rows;
  }

  public static function all(string $q = ""): array {
    $conn = DB::conn();
    if ($q !== "") {
      $like = "%{$q}%";
      $stmt = $conn->prepare("SELECT id,title,slug,image,category,author,price FROM books WHERE title LIKE ? OR author LIKE ? OR category LIKE ? ORDER BY id DESC");
      $stmt->bind_param("sss", $like, $like, $like);
    } else {
      $stmt = $conn->prepare("SELECT id,title,slug,image,category,author,price FROM books ORDER BY id DESC");
    }
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $stmt->close();
    return $rows;
  }

  public static function findBySlug(string $slug): ?array {
    $conn = DB::conn();
    $stmt = $conn->prepare("SELECT id,title,slug,image,category,description,author,price FROM books WHERE slug=? LIMIT 1");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row ?: null;
  }

  public static function find(int $id): ?array {
    $conn = DB::conn();
    $stmt = $conn->prepare("SELECT * FROM books WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row ?: null;
  }

  public static function slugExists(string $slug, int $excludeId = 0): bool {
    $conn = DB::conn();
    if ($excludeId > 0) {
      $stmt = $conn->prepare("SELECT id FROM books WHERE slug=? AND id<>? LIMIT 1");
      $stmt->bind_param("si", $slug, $excludeId);
    } else {
      $stmt = $conn->prepare("SELECT id FROM books WHERE slug=? LIMIT 1");
      $stmt->bind_param("s", $slug);
    }
    $stmt->execute();
    $exists = $stmt->get_result()->num_rows > 0;
    $stmt->close();
    return $exists;
  }

  public static function paginateAdmin(string $q, int $page, int $perPage): array {
    $conn = DB::conn();
    $offset = ($page-1)*$perPage;

    if ($q !== "") {
      $like = "%{$q}%";
      $stmtC = $conn->prepare("SELECT COUNT(*) c FROM books WHERE title LIKE ? OR slug LIKE ? OR category LIKE ? OR author LIKE ?");
      $stmtC->bind_param("ssss", $like, $like, $like, $like);
      $stmtC->execute();
      $total = (int)$stmtC->get_result()->fetch_assoc()['c'];
      $stmtC->close();

      $stmt = $conn->prepare("SELECT id,title,slug,image,category,author,price FROM books
        WHERE title LIKE ? OR slug LIKE ? OR category LIKE ? OR author LIKE ?
        ORDER BY id DESC LIMIT ? OFFSET ?");
      $stmt->bind_param("ssssii", $like, $like, $like, $like, $perPage, $offset);
    } else {
      $total = (int)$conn->query("SELECT COUNT(*) c FROM books")->fetch_assoc()['c'];
      $stmt = $conn->prepare("SELECT id,title,slug,image,category,author,price FROM books ORDER BY id DESC LIMIT ? OFFSET ?");
      $stmt->bind_param("ii", $perPage, $offset);
    }

    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $stmt->close();
    return [$rows, $total];
  }

  public static function save(array $data, int $id = 0): int {
    $conn = DB::conn();
    $title = $data['title'];
    $slug = $data['slug'];
    $image = $data['image'];
    $category = $data['category'];
    $description = $data['description'];
    $author = $data['author'];
    $price = (float)$data['price'];

    if ($id > 0) {
      $stmt = $conn->prepare("UPDATE books SET title=?, slug=?, image=?, category=?, description=?, author=?, price=? WHERE id=?");
      $stmt->bind_param("ssssssdi", $title, $slug, $image, $category, $description, $author, $price, $id);
      $stmt->execute();
      $stmt->close();
      return $id;
    }

    // created_by is optional in your schema, but you said it exists ✅
    $createdBy = (int)Auth::id();
    $stmt = $conn->prepare("INSERT INTO books (title, slug, image, category, description, author, price, created_by) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssdi", $title, $slug, $image, $category, $description, $author, $price, $createdBy);
    $stmt->execute();
    $newId = (int)$stmt->insert_id;
    $stmt->close();
    return $newId;
  }

  public static function delete(int $id): bool {
    $conn = DB::conn();
    $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return (bool)$ok;
  }
}

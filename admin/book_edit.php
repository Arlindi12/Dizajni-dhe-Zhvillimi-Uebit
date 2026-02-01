<?php
include __DIR__ . "/../includes/admin_guard.php";
include __DIR__ . "/../includes/flash.php";
include __DIR__ . "/../includes/db.php";

$id = (int)($_GET["id"] ?? 0);
$isEdit = $id > 0;

$book = [
  "title" => "",
  "slug" => "",
  "image" => "",
  "category" => "",
  "description" => "",
  "author" => "",
  "price" => "0.00",
];

function startsWith(string $haystack, string $needle): bool {
  return $needle === "" || strncmp($haystack, $needle, strlen($needle)) === 0;
}

function slugify(string $text): string {
  $text = strtolower(trim($text));
  $text = preg_replace('/[^a-z0-9]+/', '-', $text);
  return trim($text, '-');
}

function booksHasCreatedBy(mysqli $conn): bool {
  // checks if "created_by" column exists (optional feature)
  $dbRes = $conn->query("SELECT DATABASE() db");
  $dbRow = $dbRes ? $dbRes->fetch_assoc() : null;
  $dbName = $dbRow["db"] ?? "";
  if ($dbRes) $dbRes->close();

  if ($dbName === "") return false;

  $stmt = $conn->prepare("
    SELECT COUNT(*) c
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA=? AND TABLE_NAME='books' AND COLUMN_NAME='created_by'
  ");
  $stmt->bind_param("s", $dbName);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  return ((int)($row["c"] ?? 0)) > 0;
}

$hasCreatedBy = booksHasCreatedBy($conn);

if ($isEdit) {
  $stmt = $conn->prepare("SELECT * FROM books WHERE id=? LIMIT 1");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  $stmt->close();
  if (!$row) die("Book not found.");
  $book = $row;
}

// flash messages (shown after redirects)
$flashSuccess = flash_get("success");
$flashError = flash_get("error");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = trim($_POST["title"] ?? "");
  $slug  = trim($_POST["slug"] ?? "");
  $image = trim($_POST["image"] ?? ""); // optional manual
  $category = trim($_POST["category"] ?? "");
  $description = trim($_POST["description"] ?? "");
  $author = trim($_POST["author"] ?? "");
  $price = trim($_POST["price"] ?? "0.00");

  $error = "";

  if ($title === "" || strlen($title) < 2) {
    $error = "Title is required.";
  } else {
    if ($slug === "") $slug = slugify($title);
    if (!preg_match('/^[a-z0-9\-]+$/', $slug)) $error = "Slug must be lowercase, numbers, and hyphens only.";
    elseif ($price !== "" && !is_numeric($price)) $error = "Price must be a number.";
  }

  // ✅ Handle file upload (optional) - stronger server-side checks
  if (!$error && isset($_FILES["image_file"]) && ($_FILES["image_file"]["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    $uploadErr = (int)($_FILES["image_file"]["error"] ?? UPLOAD_ERR_NO_FILE);
    if ($uploadErr !== UPLOAD_ERR_OK) {
      $error = "Upload failed. Try again.";
    } else {
      $tmp  = $_FILES["image_file"]["tmp_name"];
      $orig = $_FILES["image_file"]["name"] ?? "image";
      $size = (int)($_FILES["image_file"]["size"] ?? 0);

      // Size limit: 3MB
      if ($size <= 0 || $size > 3 * 1024 * 1024) {
        $error = "Image must be under 3MB.";
      } else {
        $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
        $allowedExt = ["jpg","jpeg","png","webp"];
        if (!in_array($ext, $allowedExt, true)) {
          $error = "Only JPG, PNG, WEBP allowed.";
        } else {
          // MIME check
          $mime = "";
          if (function_exists("finfo_open")) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
              $mime = (string)finfo_file($finfo, $tmp);
              finfo_close($finfo);
            }
          }

          $allowedMime = ["image/jpeg","image/png","image/webp"];
          if ($mime !== "" && !in_array($mime, $allowedMime, true)) {
            $error = "Invalid image type (MIME check failed).";
          } else {
            // Real image check
            $imgInfo = @getimagesize($tmp);
            if ($imgInfo === false) {
              $error = "Invalid image file.";
            } else {
              $uploadDir = __DIR__ . "/../images/books";
              if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

              $safeBase = preg_replace('/[^a-z0-9\-]+/i', '-', pathinfo($orig, PATHINFO_FILENAME));
              $filename = $slug . "-" . time() . "-" . $safeBase . "." . $ext;
              $dest = $uploadDir . "/" . $filename;

              if (!move_uploaded_file($tmp, $dest)) {
                $error = "Could not save image file.";
              } else {
                // Store relative path
                $image = "images/books/" . $filename;
              }
            }
          }
        }
      }
    }
  }

  // Normalize manual image: "Photo1.jpg" -> "images/Photo1.jpg"
  if (!$error && $image !== "" && !startsWith($image, "images/") && !startsWith($image, "http")) {
    $image = "images/" . ltrim($image, "/");
  }

  // Slug unique
  if (!$error) {
    if ($isEdit) {
      $stmt = $conn->prepare("SELECT id FROM books WHERE slug=? AND id<>? LIMIT 1");
      $stmt->bind_param("si", $slug, $id);
    } else {
      $stmt = $conn->prepare("SELECT id FROM books WHERE slug=? LIMIT 1");
      $stmt->bind_param("s", $slug);
    }
    $stmt->execute();
    $exists = $stmt->get_result()->num_rows > 0;
    $stmt->close();

    if ($exists) $error = "Slug already exists. Choose another.";
  }

  if ($error) {
    flash_set("error", $error);
    // keep entered data on page
    $book = ["id"=>$id,"title"=>$title,"slug"=>$slug,"image"=>$image,"category"=>$category,"description"=>$description,"author"=>$author,"price"=>$price];
    header("Location: book_edit.php" . ($isEdit ? "?id=" . (int)$id : ""));
    exit;
  }

  // Save to DB
  if ($isEdit) {
    $stmt = $conn->prepare("UPDATE books SET title=?, slug=?, image=?, category=?, description=?, author=?, price=? WHERE id=?");
    $stmt->bind_param("ssssssdi", $title, $slug, $image, $category, $description, $author, $price, $id);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
      flash_set("success", "Book updated successfully.");
      header("Location: book_edit.php?id=" . (int)$id);
      exit;
    } else {
      flash_set("error", "DB error while saving.");
      header("Location: book_edit.php?id=" . (int)$id);
      exit;
    }
  } else {
    // created_by (optional)
    if ($hasCreatedBy) {
      $createdBy = (int)($_SESSION["user_id"] ?? 0);
      $stmt = $conn->prepare("INSERT INTO books (title, slug, image, category, description, author, price, created_by)
                              VALUES (?,?,?,?,?,?,?,?)");
      $stmt->bind_param("ssssssdi", $title, $slug, $image, $category, $description, $author, $price, $createdBy);
    } else {
      $stmt = $conn->prepare("INSERT INTO books (title, slug, image, category, description, author, price)
                              VALUES (?,?,?,?,?,?,?)");
      $stmt->bind_param("ssssssd", $title, $slug, $image, $category, $description, $author, $price);
    }

    $ok = $stmt->execute();
    $newId = (int)$stmt->insert_id;
    $stmt->close();

    if ($ok) {
      flash_set("success", "Book added successfully.");
      header("Location: book_edit.php?id=" . $newId);
      exit;
    } else {
      flash_set("error", "DB error while saving.");
      header("Location: book_edit.php");
      exit;
    }
  }
}

$pageTitle = $isEdit ? "Admin | Edit Book" : "Admin | Add Book";
$activePage = "admin";
$requireAuth = true;
include __DIR__ . "/../includes/header.php";

// Reload book after redirect (edit page)
if ($isEdit) {
  $stmt = $conn->prepare("SELECT * FROM books WHERE id=? LIMIT 1");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  $stmt->close();
  if ($row) $book = $row;
}
?>

<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2><?= $isEdit ? "Edit Book #".(int)$id : "Add Book" ?></h2>
    <a class="btn-main" href="books.php">← Back</a>
  </div>

  <?php if ($flashError): ?><div class="alert error"><?= htmlspecialchars($flashError) ?></div><?php endif; ?>
  <?php if ($flashSuccess): ?><div class="alert success"><?= htmlspecialchars($flashSuccess) ?></div><?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="form-grid" style="max-width:820px; margin-top:12px;">
    <div class="form-row">
      <label>Title</label>
      <input name="title" value="<?= htmlspecialchars($book["title"] ?? "") ?>" required>
    </div>

    <div class="form-row">
      <label>Slug (leave empty to auto-generate)</label>
      <input name="slug" value="<?= htmlspecialchars($book["slug"] ?? "") ?>">
    </div>

    <div class="form-row">
      <label>Upload Image (JPG/PNG/WEBP, max 3MB)</label>
      <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp">
      <div style="opacity:.75; font-size:13px; margin-top:6px;">
        Upload overrides the Image Path field.
      </div>
    </div>

    <div class="form-row">
      <label>Image Path (optional) - example: Photo1.jpg or images/Photo1.jpg</label>
      <input name="image" value="<?= htmlspecialchars($book["image"] ?? "") ?>">
    </div>

    <div class="form-row">
      <label>Category</label>
      <input name="category" value="<?= htmlspecialchars($book["category"] ?? "") ?>">
    </div>

    <div class="form-row">
      <label>Author</label>
      <input name="author" value="<?= htmlspecialchars($book["author"] ?? "") ?>">
    </div>

    <div class="form-row">
      <label>Price (example: 12.50)</label>
      <input name="price" value="<?= htmlspecialchars((string)($book["price"] ?? "0.00")) ?>">
    </div>

    <div class="form-row">
      <label>Description</label>
      <textarea name="description" rows="6"><?= htmlspecialchars($book["description"] ?? "") ?></textarea>
    </div>

    <div class="actions" style="margin-top:6px;">
      <button class="btn-main" type="submit">Save</button>
      <?php if (!empty($book["image"])): ?>
        <a class="btn-main" href="<?= htmlspecialchars($book["image"]) ?>" target="_blank">Preview Image</a>
      <?php endif; ?>
    </div>
  </form>
</section>

<?php
$conn->close();
include __DIR__ . "/../includes/footer.php";
?>

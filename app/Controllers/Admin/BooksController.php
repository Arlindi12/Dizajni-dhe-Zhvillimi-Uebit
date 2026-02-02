<?php
namespace Admin;

class BooksController extends \Controller {

  private function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
  }

  public function index(): void {
    \Auth::requireAdmin();

    $pageTitle = "Admin | Books";
    $activePage = "admin";

    $flashSuccess = \Session::flashGet("success");
    $flashError = \Session::flashGet("error");

    $q = trim($_GET["q"] ?? "");
    $page = max(1, (int)($_GET["page"] ?? 1));
    $perPage = 10;

    [$books, $total] = \Book::paginateAdmin($q, $page, $perPage);
    $totalPages = max(1, (int)ceil($total / $perPage));

    $this->view("admin/books/index", compact(
      "pageTitle","activePage","books","q","page","total","totalPages","flashSuccess","flashError"
    ));
  }

  public function edit(): void {
    \Auth::requireAdmin();

    $pageTitle = "Admin | Edit Book";
    $activePage = "admin";

    $flashSuccess = \Session::flashGet("success");
    $flashError = \Session::flashGet("error");

    $id = (int)($_GET["id"] ?? 0);
    $isEdit = $id > 0;

    $book = $isEdit ? \Book::find($id) : [
      "title" => "", "slug" => "", "image" => "", "category" => "",
      "description" => "", "author" => "", "price" => "0.00",
    ];

    if ($isEdit && !$book) die("Book not found.");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      \Csrf::check();

      $title = trim($_POST["title"] ?? "");
      $slug  = trim($_POST["slug"] ?? "");
      $image = trim($_POST["image"] ?? "");
      $category = trim($_POST["category"] ?? "");
      $description = trim($_POST["description"] ?? "");
      $author = trim($_POST["author"] ?? "");
      $price = trim($_POST["price"] ?? "0.00");

      $error = "";
      if ($title === "" || strlen($title) < 2) $error = "Title is required.";
      else {
        if ($slug === "") $slug = $this->slugify($title);
        if (!preg_match('/^[a-z0-9\-]+$/', $slug)) $error = "Slug must be lowercase, numbers, and hyphens only.";
        elseif ($price !== "" && !is_numeric($price)) $error = "Price must be a number.";
      }

      // upload image optional
      if (
        !$error &&
        isset($_FILES["image_file"]) &&
        (($_FILES["image_file"]["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE)
      ) {
        $uploadErr = (int)($_FILES["image_file"]["error"] ?? UPLOAD_ERR_NO_FILE);
        if ($uploadErr !== UPLOAD_ERR_OK) $error = "Upload failed. Try again.";
        else {
          $tmp = $_FILES["image_file"]["tmp_name"];
          $orig = $_FILES["image_file"]["name"] ?? "image";
          $size = (int)($_FILES["image_file"]["size"] ?? 0);

          if ($size <= 0 || $size > 3 * 1024 * 1024) $error = "Image must be under 3MB.";
          else {
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            $allowedExt = ["jpg","jpeg","png","webp"];
            if (!in_array($ext, $allowedExt, true)) $error = "Only JPG, PNG, WEBP allowed.";
            else {
              $info = @getimagesize($tmp);
              if ($info === false) $error = "Invalid image file.";
              else {
                $dir = __DIR__ . "/../../../public/images/books";
                if (!is_dir($dir)) mkdir($dir, 0777, true);

                $safeBase = preg_replace('/[^a-z0-9\-]+/i', '-', pathinfo($orig, PATHINFO_FILENAME));
                $filename = $slug . "-" . time() . "-" . $safeBase . "." . $ext;
                $dest = $dir . "/" . $filename;

                if (!move_uploaded_file($tmp, $dest)) $error = "Could not save image file.";
                else $image = "images/books/" . $filename; // stored relative to /public
              }
            }
          }
        }
      }

      if (
        !$error &&
        $image !== "" &&
        !str_starts_with($image, "images/") &&
        !str_starts_with($image, "http")
      ) {
        $image = "images/" . ltrim($image, "/");
      }

      if (!$error && \Book::slugExists($slug, $isEdit ? $id : 0)) {
        $error = "Slug already exists. Choose another.";
      }

      if ($error) {
        \Session::flashSet("error", $error);
        $this->redirect($isEdit ? "/admin/books/edit?id=".$id : "/admin/books/edit");
      }

      $newId = \Book::save([
        "title"=>$title,"slug"=>$slug,"image"=>$image,"category"=>$category,
        "description"=>$description,"author"=>$author,"price"=>$price,
      ], $isEdit ? $id : 0);

      \Session::flashSet("success", $isEdit ? "Book updated successfully." : "Book added successfully.");
      $this->redirect("/admin/books/edit?id=".$newId);
    }

    $this->view("admin/books/edit", compact(
      "pageTitle","activePage","book","isEdit","id","flashSuccess","flashError"
    ));
  }

  public function delete(): void {
    \Auth::requireAdmin();
    \Csrf::check();

    $id = (int)($_POST["id"] ?? 0);
    if ($id <= 0) die("Invalid id.");

    \Book::delete($id);
    \Session::flashSet("success", "Book deleted successfully.");
    $this->redirect("/admin/books");
  }
}

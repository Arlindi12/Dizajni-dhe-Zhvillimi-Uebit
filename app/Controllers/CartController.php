<?php

class CartController extends Controller {

  private function getCart(): array {
    $cart = Session::get("cart", []);
    return is_array($cart) ? $cart : [];
  }

  public function index(): void {
    Auth::requireLogin();

    $pageTitle = "BookNest | Karroca";
    $activePage = "cart";

    $cart = $this->getCart();
    $items = [];
    $total = 0.0;

    if (!empty($cart)) {
      $ids = array_keys($cart);
      $ids = array_filter(array_map("intval", $ids), fn($x)=>$x>0);
      if ($ids) {
        $placeholders = implode(",", array_fill(0, count($ids), "?"));
        $types = str_repeat("i", count($ids));
        $sql = "SELECT id,title,slug,image,price FROM books WHERE id IN ($placeholders)";
        $conn = DB::conn();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$ids);
        $stmt->execute();
        $res = $stmt->get_result();
        $byId = [];
        while ($r = $res->fetch_assoc()) $byId[(int)$r["id"]] = $r;
        $stmt->close();

        foreach ($ids as $id) {
          if (!isset($byId[$id])) continue;
          $q = (int)($cart[$id] ?? 0);
          if ($q <= 0) continue;

          $b = $byId[$id];
          $price = (float)($b["price"] ?? 0);
          $sub = $price * $q;
          $total += $sub;

          $items[] = [
            "id" => $id,
            "title" => $b["title"],
            "slug" => $b["slug"],
            "image" => $b["image"],
            "price" => $price,
            "qty" => $q,
            "subtotal" => $sub,
          ];
        }
      }
    }

    $this->view("pages/cart", compact("pageTitle","activePage","items","total"));
  }

  public function add(): void {
    Auth::requireLogin();
    Csrf::check();

    $bookId = (int)($_POST["book_id"] ?? 0);
    $redirect = trim($_POST["redirect"] ?? "/cart");
    if ($redirect === "") $redirect = "/cart";

    if ($bookId <= 0) $this->redirect($redirect);

    $book = Book::find($bookId);
    if (!$book) $this->redirect($redirect);

    $cart = $this->getCart();
    $cart[$bookId] = (int)($cart[$bookId] ?? 0) + 1;
    Session::set("cart", $cart);

    $this->redirect($redirect);
  }

  public function update(): void {
    Auth::requireLogin();
    Csrf::check();

    $qty = $_POST["qty"] ?? [];
    $cart = $this->getCart();

    if (is_array($qty)) {
      foreach ($qty as $idStr => $qStr) {
        $id = (int)$idStr;
        $q = (int)$qStr;
        if ($id <= 0) continue;
        if ($q <= 0) unset($cart[$id]);
        else $cart[$id] = $q;
      }
    }

    Session::set("cart", $cart);
    $this->redirect("/cart");
  }

  public function remove(): void {
    Auth::requireLogin();
    Csrf::check();

    $bookId = (int)($_POST["book_id"] ?? 0);
    $cart = $this->getCart();
    if ($bookId > 0) unset($cart[$bookId]);
    Session::set("cart", $cart);

    $this->redirect("/cart");
  }

  public function clear(): void {
    Auth::requireLogin();
    Csrf::check();

    Session::forget("cart");
    $this->redirect("/cart");
  }
}

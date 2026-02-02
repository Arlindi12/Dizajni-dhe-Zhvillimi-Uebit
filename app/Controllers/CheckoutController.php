<?php

class CheckoutController extends Controller {
  public function index(): void {
    Auth::requireLogin();

    $pageTitle = "BookNest | Checkout";
    $activePage = "cart";

    $cart = Session::get("cart", []);
    if (!is_array($cart)) $cart = [];

    $items = [];
    $total = 0.0;

    if (!empty($cart)) {
      $ids = array_keys($cart);
      $ids = array_filter(array_map("intval", $ids), fn($x)=>$x>0);

      if ($ids) {
        $placeholders = implode(",", array_fill(0, count($ids), "?"));
        $types = str_repeat("i", count($ids));
        $sql = "SELECT id,title,price FROM books WHERE id IN ($placeholders)";
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

          $price = (float)($byId[$id]["price"] ?? 0);
          $sub = $price * $q;
          $total += $sub;

          $items[] = [
            "book_id" => $id,
            "title" => $byId[$id]["title"],
            "qty" => $q,
            "price" => $price,
            "subtotal" => $sub,
          ];
        }
      }
    }

    $error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      Csrf::check();

      $fullName = trim($_POST["full_name"] ?? "");
      $phone = trim($_POST["phone"] ?? "");
      $address = trim($_POST["address"] ?? "");
      $payment = trim($_POST["payment"] ?? "cash");

      if (empty($items)) $error = "Karroca është e zbrazët.";
      elseif (strlen($fullName) < 3) $error = "Emri duhet të ketë të paktën 3 karaktere.";
      elseif (strlen($phone) < 6) $error = "Numri i telefonit nuk është valid.";
      elseif (strlen($address) < 5) $error = "Adresa duhet të ketë të paktën 5 karaktere.";
      elseif (!in_array($payment, ["cash","card"], true)) $error = "Mënyra e pagesës nuk është valide.";
      else {
        $userId = Auth::id();
        $orderId = Order::create($userId, $fullName, $phone, $address, $payment, $total, $items);
        if ($orderId <= 0) $error = "Gabim gjatë krijimit të porosisë.";
        else {
          Session::forget("cart");
          $this->redirect("/my-orders?created=1");
        }
      }
    }

    $this->view("pages/checkout", compact("pageTitle","activePage","items","total","error"));
  }
}

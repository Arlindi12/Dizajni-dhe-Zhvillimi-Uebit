<?php
namespace Admin;

class OrdersController extends \Controller {
  public function index(): void {
    \Auth::requireAdmin();

    $pageTitle = "Admin | Orders";
    $activePage = "admin";

    $flashSuccess = \Session::flashGet("success");
    $flashError = \Session::flashGet("error");

    $q = trim($_GET["q"] ?? "");
    $status = trim($_GET["status"] ?? "");

    $page = max(1, (int)($_GET["page"] ?? 1));
    $perPage = 10;

    [$orders, $total] = \Order::paginateAdmin($q, $status, $page, $perPage);
    $totalPages = max(1, (int)ceil($total / $perPage));

    $this->view("admin/orders/index", compact(
      "pageTitle","activePage","orders","q","status","page","total","totalPages","flashSuccess","flashError"
    ));
  }

  // ✅ RENAMED from view() -> show()
  public function show(): void {
    \Auth::requireAdmin();

    $pageTitle = "Admin | Order";
    $activePage = "admin";

    $flashSuccess = \Session::flashGet("success");
    $flashError = \Session::flashGet("error");

    $id = (int)($_GET["id"] ?? 0);
    if ($id <= 0) die("Invalid order id.");

    $order = \Order::findAdmin($id);
    if (!$order) die("Order not found.");

    $items = \Order::itemsAdmin($id);

    $this->view("admin/orders/view", compact(
      "pageTitle","activePage","order","items","flashSuccess","flashError"
    ));
  }

  public function status(): void {
    \Auth::requireAdmin();
    \Csrf::check();

    $id = (int)($_POST["id"] ?? 0);
    $status = trim($_POST["status"] ?? "");
    $allowed = ["pending","confirmed","refused"];

    if ($id <= 0) {
      \Session::flashSet("error", "Invalid order id.");
      $this->redirect("/admin/orders");
    }
    if (!in_array($status, $allowed, true)) {
      \Session::flashSet("error", "Invalid status.");
      $this->redirect("/admin/orders/view?id=".$id);
    }

    $ok = \Order::updateStatus($id, $status);

    if ($ok) \Session::flashSet("success", "Order status updated to: ".$status);
    else \Session::flashSet("error", "DB error while updating status.");

    $this->redirect("/admin/orders/view?id=".$id);
  }
}

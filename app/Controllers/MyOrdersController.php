<?php

class MyOrdersController extends Controller {
  public function index(): void {
    Auth::requireLogin();

    $pageTitle = "BookNest | Porositë e mia";
    $activePage = "orders";

    $orders = Order::myOrders(Auth::id());

    $this->view("pages/my_orders", compact("pageTitle","activePage","orders"));
  }

  public function details(): void {
    Auth::requireLogin();

    $pageTitle = "BookNest | Detajet e porosisë";
    $activePage = "orders";

    $id = (int)($_GET["id"] ?? 0);
    if ($id <= 0) die("Invalid order.");

    $order = Order::myOrder(Auth::id(), $id);
    if (!$order) die("Order not found.");

    $items = Order::items($id);

    $this->view("pages/order_details", compact("pageTitle","activePage","order","items"));
  }
}

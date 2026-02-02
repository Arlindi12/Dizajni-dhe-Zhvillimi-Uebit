<?php
namespace Admin;

class DashboardController extends \Controller {
  public function index(): void {
    \Auth::requireAdmin();

    $pageTitle = "Admin | Dashboard";
    $activePage = "admin";

    $counts = \Order::counts();

    $this->view("admin/dashboard", [
      "pageTitle" => $pageTitle,
      "activePage" => $activePage,
      "usersCount" => $counts["users"],
      "booksCount" => $counts["books"],
      "contactsCount" => $counts["contacts"],
      "ordersCount" => $counts["orders"],
      "pendingCount" => $counts["pending"],
    ]);
  }
}

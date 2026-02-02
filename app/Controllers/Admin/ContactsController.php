<?php
namespace Admin;

class ContactsController extends \Controller {

  public function index(): void {
    \Auth::requireAdmin();

    $pageTitle  = "Admin | Contacts";
    $activePage = "admin";

    $flashSuccess = \Session::flashGet("success");
    $flashError   = \Session::flashGet("error");

    $q = trim($_GET["q"] ?? "");
    $page = max(1, (int)($_GET["page"] ?? 1));
    $perPage = 10;

    [$messages, $total] = \ContactMessage::paginate($q, $page, $perPage);
    $totalPages = max(1, (int)ceil($total / $perPage));

    // NOTE: calling parent's view() helper is fine
    $this->view("admin/contacts/index", compact(
      "pageTitle","activePage","messages","q","page","total","totalPages","flashSuccess","flashError"
    ));
  }

  // ✅ RENAMED from view() -> show()
  public function show(): void {
    \Auth::requireAdmin();

    $pageTitle  = "Admin | View Message";
    $activePage = "admin";

    $id = (int)($_GET["id"] ?? 0);
    if ($id <= 0) die("Invalid message id.");

    $msg = \ContactMessage::find($id);
    if (!$msg) die("Message not found.");

    $this->view("admin/contacts/view", compact("pageTitle","activePage","msg"));
  }

  public function delete(): void {
    \Auth::requireAdmin();
    \Csrf::check();

    $id = (int)($_POST["id"] ?? 0);
    if ($id <= 0) die("Invalid id.");

    \ContactMessage::delete($id);
    \Session::flashSet("success", "Message deleted successfully.");
    $this->redirect("/admin/contacts");
  }
}

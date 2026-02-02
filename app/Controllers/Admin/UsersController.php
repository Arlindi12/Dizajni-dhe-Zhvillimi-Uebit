<?php
namespace Admin;

class UsersController extends \Controller {
  public function index(): void {
    \Auth::requireAdmin();

    $pageTitle = "Admin | Users";
    $activePage = "admin";

    $flashSuccess = \Session::flashGet("success");
    $flashError = \Session::flashGet("error");

    $q = trim($_GET["q"] ?? "");
    $page = max(1, (int)($_GET["page"] ?? 1));
    $perPage = 10;

    [$users, $total] = \User::paginate($q, $page, $perPage);
    $totalPages = max(1, (int)ceil($total / $perPage));

    $this->view("admin/users/index", compact(
      "pageTitle","activePage","users","q","page","total","totalPages","flashSuccess","flashError"
    ));
  }

  public function edit(): void {
    \Auth::requireAdmin();

    $pageTitle = "Admin | Edit User";
    $activePage = "admin";

    $id = (int)($_GET["id"] ?? 0);
    if ($id <= 0) die("Invalid user id.");

    $user = \User::find($id);
    if (!$user) die("User not found.");

    $success = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      \Csrf::check();

      $name = trim($_POST["name"] ?? "");
      $email = trim($_POST["email"] ?? "");
      $role = (string)($_POST["role"] ?? "user");
      $newPass = (string)($_POST["new_password"] ?? "");

      if (strlen($name) < 3) $error = "Name must be at least 3 chars.";
      elseif (!\Validator::email($email)) $error = "Invalid email.";
      elseif (!in_array($role, ["user","admin"], true)) $error = "Invalid role.";

      if (!$error && \Auth::id() === (int)$id && $role !== "admin") {
        $error = "You cannot remove your own admin role.";
      }

      if (!$error && ($user["role"] ?? "") === "admin" && $role !== "admin") {
        if (\User::countAdmins() <= 1) $error = "You cannot remove the last admin.";
      }

      if (!$error) {
        $ok = \User::update($id, $name, $email, $role);
        if (!$ok) $error = "DB error while saving.";
        else {
          if ($newPass !== "") {
            if (strlen($newPass) < 6) $error = "Password must be at least 6 chars.";
            else \User::setPassword($id, $newPass);
          }
          if (!$error) $success = "Saved successfully.";
          $user = \User::find($id) ?: $user;
        }
      }
    }

    $this->view("admin/users/edit", compact("pageTitle","activePage","user","success","error"));
  }

  public function delete(): void {
    \Auth::requireAdmin();
    \Csrf::check();

    $id = (int)($_POST["id"] ?? 0);
    if ($id <= 0) die("Invalid id.");

    if (\Auth::id() === $id) {
      \Session::flashSet("error", "You cannot delete your own account.");
      $this->redirect("/admin/users");
    }

    $target = \User::find($id);
    if (!$target) {
      \Session::flashSet("error", "User not found.");
      $this->redirect("/admin/users");
    }

    if (($target["role"] ?? "") === "admin" && \User::countAdmins() <= 1) {
      \Session::flashSet("error", "You cannot delete the last admin.");
      $this->redirect("/admin/users");
    }

    \User::delete($id);
    \Session::flashSet("success", "User deleted successfully.");
    $this->redirect("/admin/users");
  }
}

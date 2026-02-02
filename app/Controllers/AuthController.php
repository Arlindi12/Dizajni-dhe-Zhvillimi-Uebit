<?php

class AuthController extends Controller {

  public function login(): void {
    $pageTitle = "BookNest | Login";
    $useAuthCss = true;

    $error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      Csrf::check();

      $email = trim($_POST["email"] ?? "");
      $pass  = (string)($_POST["password"] ?? "");

      $user = User::findByEmail($email);

      if (!$user) $error = "User nuk u gjet!";
      elseif (!password_verify($pass, $user["password"])) $error = "Password është gabim!";
      else {
        Auth::login($user);
        if (($user["role"] ?? "user") === "admin") $this->redirect("/admin/dashboard");
        $this->redirect("/");
      }
    }

    // render without main layout? we still use layout, but auth uses special css in header
    $this->view("auth/login", compact("pageTitle","useAuthCss","error"));
  }

  public function register(): void {
    $pageTitle = "BookNest | Register";
    $useAuthCss = true;

    $success = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      Csrf::check();

      $name  = trim($_POST["name"] ?? "");
      $email = trim($_POST["email"] ?? "");
      $pass  = (string)($_POST["password"] ?? "");

      if (strlen($name) < 3) $error = "Emri duhet të ketë të paktën 3 karaktere.";
      elseif (!Validator::email($email)) $error = "Email nuk është valid.";
      elseif (strlen($pass) < 6) $error = "Password duhet të ketë të paktën 6 karaktere.";
      else {
        if (User::findByEmail($email)) $error = "Ky email është përdorur!";
        else {
          $ok = User::create($name, $email, $pass);
          $success = $ok ? "✅ U regjistrua me sukses! Tani mund të hyni." : "❌ Gabim gjatë regjistrimit!";
        }
      }
    }

    $this->view("auth/register", compact("pageTitle","useAuthCss","success","error"));
  }

  public function logout(): void {
    Auth::logout();
    $this->redirect("/login");
  }
}

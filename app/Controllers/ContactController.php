<?php

class ContactController extends Controller {
  public function index(): void {
    Auth::requireLogin();

    $pageTitle = "BookNest | Kontakt";
    $activePage = "contact";

    $success = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      Csrf::check();

      $name = trim($_POST["name"] ?? "");
      $email = trim($_POST["email"] ?? "");
      $subject = trim($_POST["subject"] ?? "");
      $message = trim($_POST["message"] ?? "");

      if (strlen($name) < 3) $error = "Emri duhet të ketë të paktën 3 karaktere.";
      elseif (!Validator::email($email)) $error = "Email nuk është valid.";
      elseif (strlen($subject) < 3) $error = "Subjekti duhet të ketë të paktën 3 karaktere.";
      elseif (strlen($message) < 10) $error = "Mesazhi duhet të ketë të paktën 10 karaktere.";
      else {
        $ok = ContactMessage::create($name, $email, $subject, $message);
        if ($ok) $success = "✅ Mesazhi u dërgua me sukses!";
        else $error = "❌ Gabim gjatë dërgimit!";
      }
    }

    $this->view("pages/contact", compact("pageTitle","activePage","success","error"));
  }
}

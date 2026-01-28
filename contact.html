<?php
$pageTitle = 'BookNest | Kontakt';
$activePage = 'contact';
$requireAuth = true;
include __DIR__ . '/includes/header.php';

$conn = new mysqli("localhost", "root", "", "libraria");
if ($conn->connect_error) die("DB error");

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST["name"] ?? "");
  $email = trim($_POST["email"] ?? "");
  $message = trim($_POST["message"] ?? "");

  if (strlen($name) < 3) {
    $error = "Emri duhet të ketë të paktën 3 karaktere.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Email nuk është valid.";
  } elseif (strlen($message) < 10) {
    $error = "Mesazhi duhet të ketë të paktën 10 karaktere.";
  } else {
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
      $success = "✅ Mesazhi u dërgua me sukses!";
    } else {
      $error = "❌ Gabim gjatë dërgimit!";
    }
    $stmt->close();
  }
}

$conn->close();
?>

<section class="contact-container">
  <h2>Kontakt</h2>

  <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <form method="POST">
    <label>Emri</label>
    <input type="text" name="name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Mesazhi</label>
    <textarea name="message" rows="6" required></textarea>

    <button type="submit">Dërgo</button>
  </form>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

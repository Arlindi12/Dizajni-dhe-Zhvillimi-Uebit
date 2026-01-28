<?php
$pageTitle = 'BookNest | Register';
$activePage = 'auth';
$useAuthCss = true;
include __DIR__ . '/includes/header.php';

$conn = new mysqli("localhost", "root", "", "libraria");
if ($conn->connect_error) die("DB error");

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name  = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $pass  = $_POST["password"] ?? "";

    if (strlen($name) < 3) {
        $error = "Emri duhet të ketë të paktën 3 karaktere.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email nuk është valid.";
    } elseif (strlen($pass) < 6) {
        $error = "Password duhet të ketë të paktën 6 karaktere.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check && $check->num_rows > 0) {
            $error = "Ky email është përdorur!";
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $stmt2 = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
            $stmt2->bind_param("sss", $name, $email, $hashed);

            if ($stmt2->execute()) {
                $success = "✅ U regjistrua me sukses! Tani mund të hyni.";
            } else {
                $error = "❌ Gabim gjatë regjistrimit!";
            }
            $stmt2->close();
        }

        $stmt->close();
    }
}

$conn->close();
?>

<div class="auth-wrapper">
  <div class="auth-box">
    <h2>Register</h2>

    <?php if ($success): ?>
      <div class="alert success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <label>Name:</label>
      <input type="text" name="name" required>

      <label>Email:</label>
      <input type="email" name="email" required>

      <label>Password:</label>
      <input type="password" name="password" required>

      <button type="submit">Register</button>

      <div class="link">
        Ke llogari? <a href="login.php">Hyr këtu</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

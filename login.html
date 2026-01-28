<?php
$pageTitle = 'BookNest | Login';
$activePage = 'auth';
$useAuthCss = true;
include __DIR__ . '/includes/header.php';

$conn = new mysqli("localhost", "root", "", "libraria");
if ($conn->connect_error) die("DB error");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $pass  = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($pass, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_role"] = $user["role"];

            // 🔁 Redirect based on role
            if ($user["role"] === "admin") {
              header("Location: admin/dashboard.php");
            } else {
              header("Location: index.php");
            }
            exit;
        } else {
            $error = "Password është gabim!";
        }
    } else {
        $error = "User nuk u gjet!";
    }

    $stmt->close();
}

$conn->close();
?>

<div class="auth-wrapper">
  <div class="auth-box">
    <h2>Login</h2>

    <?php if ($error): ?>
      <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <label>Email:</label>
      <input type="email" name="email" required>

      <label>Password:</label>
      <input type="password" name="password" required>

      <button type="submit">Login</button>

      <div class="link">
        Nuk ke llogari? <a href="register.php">Regjistrohu këtu</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

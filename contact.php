<?php
// ===============================
// KONFIGURIM PËR DB (Më vonë ndryshoje me tënde)
// ===============================
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "libraria";

$conn = new mysqli($host, $user, $pass, $dbname);

// Kontrollo lidhjen
if($conn->connect_error){
    die("Lidhja dështoi: " . $conn->connect_error);
}

// ===============================
// TRAJTIMI I FORMËS
// ===============================
$name = $email = $subject = $message = "";
$success = $error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Validim i thjeshtë
    if(empty($name) || empty($email) || empty($subject) || empty($message)){
        $error = "Ju lutemi plotësoni të gjitha fushat.";
    } else {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if($stmt->execute()){
            $success = "Mesazhi juaj u dërgua me sukses!";
            $email = $name = $subject = $message = ""; // Pastrimi i fushave
        } else {
            $error = "Ndodhi një gabim gjatë dërgimit të mesazhit.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>BookNest | Kontakt</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>BookNest</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="books.php">Librat</a>
        <a href="about.php">Rreth nesh</a>
        <a href="contact.php" class="active">Kontakt</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<section class="contact-container">
    <h2>Na Kontaktoni</h2>

    <?php if($error) echo "<p class='error'>$error</p>"; ?>
    <?php if($success) echo "<p class='success'>$success</p>"; ?>

    <form action="contact.php" method="post">
        <input type="text" name="name" placeholder="Emri juaj" value="<?php echo $name; ?>">
        <input type="email" name="email" placeholder="Emaili juaj" value="<?php echo $email; ?>">
        <input type="text" name="subject" placeholder="Subjekti" value="<?php echo $subject; ?>">
        <textarea name="message" placeholder="Mesazhi"><?php echo $message; ?></textarea>
        <button type="submit">Dërgo Mesazhin</button>
    </form>
</section>

<footer>
  <div class="footer-container">
    <div class="footer-column">
      <h3>Rreth Nesh</h3>
      <p>BookNest është libraria juaj online me librat më të mirë për çdo moshë.</p>
    </div>
    <div class="footer-column">
      <h3>Quick Links</h3>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="books.php">Librat</a></li>
        <li><a href="about.php">Rreth Nesh</a></li>
        <li><a href="contact.php">Kontakt</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h3>Na ndiqni</h3>
      <p>
        <a href="#">Facebook</a> | 
        <a href="#">Instagram</a> | 
        <a href="#">Twitter</a>
      </p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; <?php echo date("Y"); ?> BookNest Library | Të gjitha të drejtat e rezervuara</p>
  </div>
</footer>

</body>
</html>

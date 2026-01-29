<?php
$pageTitle = 'BookNest | Detajet e librit';
$activePage = 'books';
$requireAuth = true;
include __DIR__ . '/includes/header.php';

// ===============================
// LIDHJA ME DB
// ===============================
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "libraria";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Lidhja dështoi: " . $conn->connect_error);
}

// Merr slug nga URL
$slug = isset($_GET['book']) ? trim($_GET['book']) : '';

// Merr librin nga DB me prepared statement
$book = null;

if ($slug !== '') {
    $stmt = $conn->prepare("SELECT * FROM books WHERE slug = ? LIMIT 1");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $book = $res->fetch_assoc();
    }
    $stmt->close();
}

// Nëse nuk u gjet, merr librin e parë
if (!$book) {
    $default = $conn->query("SELECT * FROM books ORDER BY id ASC LIMIT 1");
    $book = $default ? $default->fetch_assoc() : null;
}

if (!$book) {
    die("Nuk ka libra në databazë.");
}

// Merr slug-at për next/previous
$allBooks = $conn->query("SELECT slug FROM books ORDER BY id ASC");
$slugs = [];
while ($row = $allBooks->fetch_assoc()) {
    $slugs[] = $row['slug'];
}

$currentIndex = array_search($book['slug'], $slugs, true);
if ($currentIndex === false) $currentIndex = 0;

$prevSlug = $slugs[($currentIndex - 1 + count($slugs)) % count($slugs)];
$nextSlug = $slugs[($currentIndex + 1) % count($slugs)];
?>

<section id="bookDetails">
  <h2><?= htmlspecialchars($book['title']); ?> — Detajet</h2>

  <img src="<?= htmlspecialchars($book['image']); ?>" alt="<?= htmlspecialchars($book['title']); ?>">

  <p><strong>Autori:</strong> <?= htmlspecialchars($book['author']); ?></p>
  <p><strong>Çmimi:</strong> <?= htmlspecialchars($book['price']); ?></p>
  <p><strong>Përshkrimi:</strong> <?= htmlspecialchars($book['description']); ?></p>

  <button class="btn-main"
    onclick="alert('<?= htmlspecialchars(addslashes($book['title'])); ?> u shtua në karrocë!')">
    Shto në karrocë
  </button>

  <div class="details-nav">
    <a href="book-details.php?book=<?= urlencode($prevSlug); ?>" class="btn-main">Previous</a>
    <a href="book-details.php?book=<?= urlencode($nextSlug); ?>" class="btn-main">Next</a>
  </div>
</section>

<?php
$conn->close();
include __DIR__ . '/includes/footer.php';
?>

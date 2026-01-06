<?php
// ===============================
// DATA (për momentin statike – më vonë nga databaza)
// ===============================

$slides = [
  ["image" => "Photo6.jpg", "caption" => ""],
  ["image" => "Photo2.jpg", "caption" => "Libri më i lexuar: Përtej Hijes"],
  ["image" => "Photo3.jpg", "caption" => ""],
  ["image" => "Photo4.jpg", "caption" => "Libri i javës: 1984"],
  ["image" => "Photo5.jpg", "caption" => ""]
];

$books = [
  [
    "title" => "Zonja Bovary",
    "category" => "romane",
    "description" => "Roman klasik nga Gustave Flaubert"
  ],
  [
    "title" => "Përtej Hijes",
    "category" => "romane",
    "description" => "Roman modern i shumë lexuar"
  ],
  [
    "title" => "Gjarpërinjtë dhe Gëzofi",
    "category" => "histori",
    "description" => "Historia e një qyteti të vjetër"
  ],
  [
    "title" => "Fletë Magjike",
    "category" => "femije",
    "description" => "Libër për fëmijë me ilustrime"
  ]
];
?>

<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <title>BookNest | Home</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
  <h1>BookNest</h1>
  <nav>
    <a href="index.php" class="active">Home</a>
    <a href="books.php">Librat</a>
    <a href="about.php">Rreth nesh</a>
    <a href="contact.php">Kontakt</a>
    <a href="login.php">Login</a>
  </nav>
</header>

<!-- ===============================
     SLIDER
================================ -->
<section class="slider">
  <div class="slides">
    <?php foreach ($slides as $index => $slide): ?>
      <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
        <img src="images/<?php echo $slide['image']; ?>" alt="Slide">
        <?php if (!empty($slide['caption'])): ?>
          <div class="caption"><?php echo $slide['caption']; ?></div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <button id="prev">&lt;</button>
  <button id="next">&gt;</button>
</section>

<!-- ===============================
     SEARCH
================================ -->
<section class="book-search">
  <h2>Kërko Libër</h2>
  <input type="text" id="searchBook" placeholder="Shkruaj emrin e librit...">
</section>

<!-- ===============================
     CATEGORIES
================================ -->
<section class="book-categories">
  <button class="tab-btn active" data-category="romane">Romanë</button>
  <button class="tab-btn" data-category="histori">Historikë</button>
  <button class="tab-btn" data-category="femije">Fëmijë</button>
</section>

<!-- ===============================
     BOOK LIST
================================ -->
<section class="book-list" id="bookList">
  <?php foreach ($books as $book): ?>
    <div class="book" data-category="<?php echo $book['category']; ?>">
      <h3><?php echo $book['title']; ?></h3>
      <p class="info-hover"><?php echo $book['description']; ?></p>
    </div>
  <?php endforeach; ?>
</section>

<!-- ===============================
     FOOTER
================================ -->
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

<!-- ===============================
     SCRIPTS
================================ -->
<script src="js/script.js"></script>
<script>
// Search bar
document.getElementById("searchBook").addEventListener("input", function() {
  const query = this.value.toLowerCase();
  document.querySelectorAll(".book").forEach(book => {
    book.style.display = book.textContent.toLowerCase().includes(query) ? "block" : "none";
  });
});

// Tabs filter
document.querySelectorAll('.tab-btn').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');

    const category = tab.dataset.category;
    document.querySelectorAll('.book').forEach(book => {
      book.style.display =
        book.dataset.category === category ? "block" : "none";
    });
  });
});
</script>

</body>
</html>

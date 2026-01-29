<?php
$pageTitle = 'BookNest | Home';
$activePage = 'home';
$requireAuth = true;
include __DIR__ . '/includes/header.php';

// ===============================
// DATA (temporary static)
// ===============================
$slides = [
  ["image" => "Photo6.jpg", "caption" => ""],
  ["image" => "Photo2.jpg", "caption" => "Libri më i lexuar: Përtej Hijes"],
  ["image" => "Photo3.jpg", "caption" => ""],
  ["image" => "Photo4.jpg", "caption" => "Libri i javës: 1984"],
  ["image" => "Photo5.jpg", "caption" => ""]
];

$books = [
  ["title" => "Zonja Bovary", "category" => "romane", "description" => "Roman klasik nga Gustave Flaubert"],
  ["title" => "Përtej Hijes", "category" => "romane", "description" => "Roman modern i shumë lexuar"],
  ["title" => "Gjarpërinjtë dhe Gëzofi", "category" => "histori", "description" => "Historia e një qyteti të vjetër"],
  ["title" => "Fletë Magjike", "category" => "femije", "description" => "Libër për fëmijë me ilustrime"]
];
?>

<section class="slider">
  <div class="slides">
    <?php foreach ($slides as $index => $slide): ?>
      <div class="slide <?= $index === 0 ? 'active' : '' ?>">
        <img src="images/<?= htmlspecialchars($slide['image']) ?>" alt="Slide">
        <?php if (!empty($slide['caption'])): ?>
          <div class="caption"><?= htmlspecialchars($slide['caption']) ?></div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <button id="prev">&lt;</button>
  <button id="next">&gt;</button>
</section>

<section class="book-search">
  <h2>Kërko Libër</h2>
  <input type="text" id="searchBook" placeholder="Shkruaj emrin e librit...">
</section>

<section class="book-categories">
  <button class="tab-btn active" data-category="romane">Romanë</button>
  <button class="tab-btn" data-category="histori">Historikë</button>
  <button class="tab-btn" data-category="femije">Fëmijë</button>
</section>

<section class="book-list" id="bookList">
  <?php foreach ($books as $book): ?>
    <div class="book" data-category="<?= htmlspecialchars($book['category']) ?>">
      <h3><?= htmlspecialchars($book['title']) ?></h3>
      <p class="info-hover"><?= htmlspecialchars($book['description']) ?></p>
    </div>
  <?php endforeach; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<?php
$pageTitle = 'BookNest | Librat';
$activePage = 'books';
$requireAuth = true;
include __DIR__ . '/includes/header.php';

$books = [
    ["title" => "Zonja Bovary", "slug" => "zonja-bovary"],
    ["title" => "Përtej Hijes", "slug" => "pertej-hijes"],
    ["title" => "Gjarpërinjtë dhe Gëzofi", "slug" => "gjarperinjte"],
    ["title" => "1984", "slug" => "1984"],
    ["title" => "Brave New World", "slug" => "brave-new-world"],
    ["title" => "Hamleti", "slug" => "hamleti"],
    ["title" => "Makbethi", "slug" => "makbethi"],
    ["title" => "Othello", "slug" => "othello"],
    ["title" => "Uliksi", "slug" => "uliksi"],
    ["title" => "Fleta e Bardhë", "slug" => "fleta-e-bardhe"],
    ["title" => "Don Kihoti", "slug" => "don-kihoti"],
    ["title" => "Triumfi i Jetës", "slug" => "triumfi-i-jetes"],
    ["title" => "Kronika e një Vdekjeje të Parapara", "slug" => "kronika-e-nje-vdekjeje"],
    ["title" => "Përtej Realitetit", "slug" => "pertej-realitetit"],
    ["title" => "Kapitulli i Parë", "slug" => "kapitulli-i-pare"],
    ["title" => "Labirinti i Mendjes", "slug" => "labirinti-i-mendjes"],
    ["title" => "Tregime të Shkurtra", "slug" => "tregime-te-shkurta"],
    ["title" => "Shpirti i Librave", "slug" => "shpirti-i-librave"],
    ["title" => "Horizontet e Reja", "slug" => "horizontet-e-reja"],
    ["title" => "Gjurmët e Kohës", "slug" => "gjurmet-e-kohes"]
];
?>

<section class="page-wrap">
  <h2>Librat Tanë</h2>

  <div class="book-list">
    <?php foreach ($books as $book): ?>
      <div class="book">
        <h3><?= htmlspecialchars($book['title']) ?></h3>
        <a class="btn-main" href="book-details.php?book=<?= urlencode($book['slug']) ?>">
          Shiko detajet
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

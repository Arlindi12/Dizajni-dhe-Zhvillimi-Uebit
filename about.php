<?php
$pageTitle = 'BookNest | Rreth nesh';
$activePage = 'about';
$requireAuth = true;
include __DIR__ . '/includes/header.php';

$team = [
  ["name" => "John Doe", "role" => "CEO & Founder", "image" => "Ekipi1.jpeg"],
  ["name" => "Jane Smith", "role" => "Head Librarian", "image" => "Ekipi2.avif"],
  ["name" => "Alex Johnson", "role" => "Customer Support", "image" => "Ekipi3.avif"],
];

$gallery = ["Photo1.jpg","Photo2.jpg","Photo3.jpg","Photo4.jpg","Photo5.jpg","Photo6.jpg"];
?>

<section class="about-intro">
  <img class="RrethNesh" src="images/Photo6.jpg" alt="Foto Libraria">
  <h2>Kush jemi ne?</h2>
  <p>
    BookNest është libraria juaj online. Ne lidhim lexuesit me librat më të mirë,
    klasikë dhe modernë, për çdo moshë.
  </p>
</section>

<section class="about-team">
  <h2>Ekipi Ynë</h2>
  <div class="team-members">
    <?php foreach($team as $m): ?>
      <div class="member">
        <img src="images/<?= htmlspecialchars($m['image']) ?>" alt="<?= htmlspecialchars($m['name']) ?>">
        <h3><?= htmlspecialchars($m['name']) ?></h3>
        <p><?= htmlspecialchars($m['role']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="about-gallery">
  <h2>Galeria e Librarisë</h2>
  <div class="slider-gallery">
    <div class="slides-gallery">
      <?php foreach($gallery as $i => $img): ?>
        <div class="slide-gallery <?= $i === 0 ? 'active' : '' ?>">
          <img src="images/<?= htmlspecialchars($img) ?>" alt="Foto <?= $i+1 ?>">
        </div>
      <?php endforeach; ?>
    </div>
    <button id="prev-gallery">&lt;</button>
    <button id="next-gallery">&gt;</button>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

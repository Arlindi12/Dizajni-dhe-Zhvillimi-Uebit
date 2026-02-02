<section class="section-card">
  <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
    <h2>Librat Tanë</h2>

    <form method="GET" class="actions" style="margin:0;">
      <input name="q" value="<?= e($q) ?>" placeholder="Kërko titull / autor / kategori..." style="max-width:320px;">
      <button class="btn-main" type="submit">Kërko</button>
      <a class="btn-main" href="<?= App::url('/books') ?>">Reset</a>
    </form>
  </div>

  <div class="book-list" style="margin-top:14px;">
    <?php foreach($books as $b): ?>
      <div class="book">
        <?php if (!empty($b['image'])): ?>
          <img src="<?= asset('/' . e($b['image'])) ?>" alt=""
               style="width:100%; height:220px; object-fit:cover; border-radius:14px; border:1px solid rgba(255,255,255,0.08);">
        <?php endif; ?>

        <h3><?= e($b['title']) ?></h3>
        <p class="info-hover">
          <?= e($b['author'] ?? '') ?> · <?= e($b['category'] ?? '') ?> · <?= e($b['price'] ?? '') ?> €
        </p>

        <a class="btn-main" href="<?= App::url('/book-details') ?>?book=<?= urlencode($b['slug']) ?>">Shiko detajet</a>
      </div>
    <?php endforeach; ?>
  </div>
</section>

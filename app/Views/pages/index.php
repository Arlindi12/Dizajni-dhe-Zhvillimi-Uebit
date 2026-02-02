<section class="slider">
  <div class="slides">
    <?php foreach ($slides as $index => $slide): ?>
      <div class="slide <?= $index === 0 ? 'active' : '' ?>">
        <img src="<?= asset('/images/' . e($slide['image'])) ?>" alt="Slide">
        <?php if (!empty($slide['caption'])): ?>
          <div class="caption"><?= e($slide['caption']) ?></div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <button id="prev">&lt;</button>
  <button id="next">&gt;</button>
</section>

<section class="section-card" style="margin-top:14px;">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2>Librat e fundit</h2>
    <a class="btn-main" href="<?= App::url('/books') ?>">Shiko më shumë libra →</a>
  </div>

  <section class="book-list" style="margin-top:14px;">
    <?php if (empty($books)): ?>
      <div class="alert error">Nuk ka libra në databazë ende. Shto libra nga Admin.</div>
    <?php else: ?>
      <?php foreach ($books as $b): ?>
        <div class="book">
          <?php if (!empty($b['image'])): ?>
            <img
              src="<?= asset('/' . e($b['image'])) ?>"
              alt=""
              style="width:100%; height:220px; object-fit:cover; border-radius:14px; border:1px solid rgba(255,255,255,0.08); margin-bottom:10px;"
            >
          <?php endif; ?>

          <h3 style="margin:0 0 6px;"><?= e($b['title']) ?></h3>
          <p class="info-hover" style="margin:0 0 10px;">
            <?= e($b['author'] ?? '') ?> · <?= e($b['price'] ?? '') ?> €
          </p>

          <div class="actions">
            <a class="btn-main" href="<?= App::url('/book-details') ?>?book=<?= urlencode($b['slug']) ?>">Shiko detajet</a>

            <form method="POST" action="<?= App::url('/cart/add') ?>" style="margin:0;">
              <?= csrf_field() ?>
              <input type="hidden" name="book_id" value="<?= (int)$b['id'] ?>">
              <input type="hidden" name="redirect" value="/">
              <button class="btn-main" type="submit">Shto në karrocë</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</section>

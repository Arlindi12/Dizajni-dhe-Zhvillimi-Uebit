<section class="section-card" id="bookDetails">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2><?= e($book['title']); ?> — Detajet</h2>
    <a class="btn-main" href="<?= App::url('/books') ?>">← Back</a>
  </div>

  <div style="display:grid; grid-template-columns: 1fr; gap:14px; margin-top:14px;">
    <?php if (!empty($book['image'])): ?>
      <img
        src="<?= asset('/' . e($book['image'])) ?>"
        alt="<?= e($book['title']); ?>"
        style="width:100%; max-height:420px; object-fit:cover; border-radius:18px; border:1px solid rgba(255,255,255,0.10);"
      >
    <?php endif; ?>

    <div class="section-card" style="padding:14px;">
      <p><strong>Autori:</strong> <?= e($book['author'] ?? ''); ?></p>
      <p><strong>Kategoria:</strong> <?= e($book['category'] ?? ''); ?></p>
      <p><strong>Çmimi:</strong> <?= e((string)($book['price'] ?? '')); ?> €</p>
      <p style="margin-top:10px;"><strong>Përshkrimi:</strong></p>
      <div class="message-text"><?= e($book['description'] ?? ''); ?></div>

      <div class="actions" style="margin-top:14px;">
        <form method="POST" action="<?= App::url('/cart/add') ?>" style="margin:0;">
          <?= csrf_field() ?>
          <input type="hidden" name="book_id" value="<?= (int)$book['id'] ?>">
          <input type="hidden" name="redirect" value="/book-details?book=<?= urlencode($book['slug']) ?>">
          <button class="btn-main" type="submit">Shto në karrocë</button>
        </form>

        <a class="btn-main" href="<?= App::url('/cart') ?>">Shiko karrocën</a>
      </div>
    </div>
  </div>
</section>

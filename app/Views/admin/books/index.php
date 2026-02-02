<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2>Books</h2>
    <div class="actions">
      <span class="badge"><?= (int)$total ?> total</span>
      <a class="btn-main" href="<?= App::url('/admin/books/edit') ?>">+ Add Book</a>
    </div>
  </div>

  <?php if (!empty($flashError)): ?><div class="alert error"><?= e($flashError) ?></div><?php endif; ?>
  <?php if (!empty($flashSuccess)): ?><div class="alert success"><?= e($flashSuccess) ?></div><?php endif; ?>

  <form method="GET" class="form-grid" style="margin-top: 12px;">
    <div class="form-row">
      <label>Search</label>
      <input name="q" value="<?= e($q) ?>" placeholder="title/slug/category/author...">
    </div>
    <div class="actions">
      <button class="btn-main" type="submit">Search</button>
      <a class="btn-main" href="<?= App::url('/admin/books') ?>">Reset</a>
    </div>
  </form>

  <div class="table-wrap" style="margin-top: 14px;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th><th>Image</th><th>Title</th><th>Slug</th><th>Category</th><th>Author</th><th>Price</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($books as $b): ?>
          <tr>
            <td><?= (int)$b["id"] ?></td>
            <td>
              <?php if (!empty($b["image"])): ?>
                <img class="thumb" src="<?= asset('/' . e($b["image"])) ?>" alt="img">
              <?php else: ?>
                <span style="opacity:.7;">—</span>
              <?php endif; ?>
            </td>
            <td><?= e($b["title"]) ?></td>
            <td><?= e($b["slug"]) ?></td>
            <td><?= e($b["category"]) ?></td>
            <td><?= e($b["author"]) ?></td>
            <td><?= e($b["price"]) ?></td>
            <td>
              <div class="actions">
                <a class="btn-main" href="<?= App::url('/admin/books/edit') ?>?id=<?= (int)$b["id"] ?>">Edit</a>

                <form method="POST" action="<?= App::url('/admin/books/delete') ?>" onsubmit="return confirm('Delete this book?');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= (int)$b["id"] ?>">
                  <button class="btn-danger" type="submit">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:14px;">
    <?php
      $base = App::url('/admin/books') . '?q=' . urlencode($q);
      $prev = max(1, $page - 1);
      $next = min($totalPages, $page + 1);
    ?>
    <a class="btn-main" href="<?= $base ?>&page=<?= $prev ?>">Prev</a>
    <span class="badge">Page <?= (int)$page ?> / <?= (int)$totalPages ?></span>
    <a class="btn-main" href="<?= $base ?>&page=<?= $next ?>">Next</a>
  </div>
</section>

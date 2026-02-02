<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2><?= $isEdit ? "Edit Book #".(int)$id : "Add Book" ?></h2>
    <a class="btn-main" href="<?= App::url('/admin/books') ?>">← Back</a>
  </div>

  <?php if (!empty($flashError)): ?><div class="alert error"><?= e($flashError) ?></div><?php endif; ?>
  <?php if (!empty($flashSuccess)): ?><div class="alert success"><?= e($flashSuccess) ?></div><?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="form-grid" style="max-width:820px; margin-top:12px;">
    <?= csrf_field() ?>

    <div class="form-row">
      <label>Title</label>
      <input name="title" value="<?= e($book["title"] ?? "") ?>" required>
    </div>

    <div class="form-row">
      <label>Slug (leave empty to auto-generate)</label>
      <input name="slug" value="<?= e($book["slug"] ?? "") ?>">
    </div>

    <div class="form-row">
      <label>Upload Image (JPG/PNG/WEBP, max 3MB)</label>
      <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp">
      <div style="opacity:.75; font-size:13px; margin-top:6px;">
        Upload overrides the Image Path field.
      </div>
    </div>

    <div class="form-row">
      <label>Image Path (optional) - example: Photo1.jpg or images/Photo1.jpg</label>
      <input name="image" value="<?= e($book["image"] ?? "") ?>">
    </div>

    <div class="form-row">
      <label>Category</label>
      <input name="category" value="<?= e($book["category"] ?? "") ?>">
    </div>

    <div class="form-row">
      <label>Author</label>
      <input name="author" value="<?= e($book["author"] ?? "") ?>">
    </div>

    <div class="form-row">
      <label>Price (example: 12.50)</label>
      <input name="price" value="<?= e((string)($book["price"] ?? "0.00")) ?>">
    </div>

    <div class="form-row">
      <label>Description</label>
      <textarea name="description" rows="6"><?= e($book["description"] ?? "") ?></textarea>
    </div>

    <div class="actions" style="margin-top:6px;">
      <button class="btn-main" type="submit">Save</button>
      <?php if (!empty($book["image"])): ?>
        <a class="btn-main" href="<?= asset('/' . e($book["image"])) ?>" target="_blank">Preview Image</a>
      <?php endif; ?>
    </div>
  </form>
</section>

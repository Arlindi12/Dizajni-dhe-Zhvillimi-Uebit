<section class="section-card" style="max-width:800px; margin:0 auto;">
  <h2>Kontakt</h2>

  <?php if (!empty($error)): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
  <?php if (!empty($success)): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>

  <form method="POST" class="form-grid">
    <?= csrf_field() ?>

    <div class="form-row">
      <label>Emri</label>
      <input type="text" name="name" value="<?= e($_POST["name"] ?? "") ?>" required>
    </div>

    <div class="form-row">
      <label>Email</label>
      <input type="email" name="email" value="<?= e($_POST["email"] ?? "") ?>" required>
    </div>

    <div class="form-row">
      <label>Subjekti</label>
      <input type="text" name="subject" value="<?= e($_POST["subject"] ?? "") ?>" required>
    </div>

    <div class="form-row">
      <label>Mesazhi</label>
      <textarea name="message" rows="6" required><?= e($_POST["message"] ?? "") ?></textarea>
    </div>

    <button class="btn-main" type="submit">Dërgo</button>
  </form>
</section>

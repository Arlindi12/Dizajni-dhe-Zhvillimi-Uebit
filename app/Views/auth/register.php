<div class="auth-wrapper">
  <div class="auth-box">
    <h2>Register</h2>

    <?php if (!empty($success)): ?>
      <div class="alert success"><?= e($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="alert error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <?= csrf_field() ?>

      <label>Name:</label>
      <input type="text" name="name" required>

      <label>Email:</label>
      <input type="email" name="email" required>

      <label>Password:</label>
      <input type="password" name="password" required>

      <button type="submit">Register</button>

      <div class="link">
        Ke llogari? <a href="<?= App::url('/login') ?>">Hyr këtu</a>
      </div>
    </form>
  </div>
</div>

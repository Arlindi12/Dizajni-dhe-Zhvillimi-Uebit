<div class="auth-wrapper">
  <div class="auth-box">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
      <div class="alert error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <?= csrf_field() ?>

      <label>Email:</label>
      <input type="email" name="email" required>

      <label>Password:</label>
      <input type="password" name="password" required>

      <button type="submit">Login</button>

      <div class="link">
        Nuk ke llogari? <a href="<?= App::url('/register') ?>">Regjistrohu këtu</a>
      </div>
    </form>
  </div>
</div>

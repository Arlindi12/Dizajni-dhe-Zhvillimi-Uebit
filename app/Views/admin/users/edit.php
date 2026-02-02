<?php
// Expected vars: $user (array), $error, $success
$user = $user ?? [];
$error = $error ?? '';
$success = $success ?? '';
?>

<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2>Edit User #<?= (int)($user['id'] ?? 0) ?></h2>
    <a class="btn-main" href="/admin/users">← Back</a>
  </div>

  <?php if (!empty($error)): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if (!empty($success)): ?><div class="alert success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <form method="POST" class="form-grid" style="max-width:620px;">
    <input type="hidden" name="id" value="<?= (int)($user['id'] ?? 0) ?>">

    <div class="form-row">
      <label>Name</label>
      <input name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
    </div>

    <div class="form-row">
      <label>Email</label>
      <input name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
    </div>

    <div class="form-row">
      <label>Role</label>
      <select name="role" required>
        <?php $role = (string)($user['role'] ?? 'user'); ?>
        <option value="user"  <?= $role === "user" ? "selected" : "" ?>>user</option>
        <option value="admin" <?= $role === "admin" ? "selected" : "" ?>>admin</option>
      </select>
    </div>

    <div class="form-row">
      <label>New Password (optional)</label>
      <input type="password" name="new_password" placeholder="Leave empty to keep current">
      <div style="opacity:.75; font-size:13px; margin-top:6px;">
        Only set this if you want to reset the user's password.
      </div>
    </div>

    <div class="actions" style="margin-top:6px;">
      <button class="btn-main" type="submit">Save</button>
      <a class="btn-danger" href="/admin/users">Cancel</a>
    </div>
  </form>
</section>

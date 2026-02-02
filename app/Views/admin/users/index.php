<?php
// Expected vars: $users (array), $q, $page, $totalPages, $total, $flashSuccess, $flashError
$q = $q ?? '';
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$total = $total ?? 0;
$users = $users ?? [];
?>

<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2>Users</h2>
    <span class="badge"><?= (int)$total ?> total</span>
  </div>

  <?php if (!empty($flashError)): ?><div class="alert error"><?= htmlspecialchars($flashError) ?></div><?php endif; ?>
  <?php if (!empty($flashSuccess)): ?><div class="alert success"><?= htmlspecialchars($flashSuccess) ?></div><?php endif; ?>

  <form method="GET" class="form-grid" style="margin-top:12px; max-width:520px;">
    <div class="form-row">
      <label>Search</label>
      <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="name/email...">
    </div>
    <div class="actions">
      <button class="btn-main" type="submit">Search</button>
      <a class="btn-main" href="/admin/users">Reset</a>
    </div>
  </form>

  <div class="table-wrap" style="margin-top: 14px;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><?= (int)($u['id'] ?? 0) ?></td>
            <td><?= htmlspecialchars($u['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
            <td>
              <span class="badge <?= (($u['role'] ?? '') === 'admin') ? 'badge-admin' : 'badge-user' ?>">
                <?= htmlspecialchars($u['role'] ?? '') ?>
              </span>
            </td>
            <td><?= htmlspecialchars($u['created_at'] ?? '') ?></td>
            <td>
              <div class="actions">
                <a class="btn-main" href="/admin/users/edit?id=<?= (int)($u['id'] ?? 0) ?>">Edit</a>

                <form method="POST" action="/admin/users/delete" onsubmit="return confirm('Delete this user?');">
                  <input type="hidden" name="id" value="<?= (int)($u['id'] ?? 0) ?>">
                  <button class="btn-danger" type="submit">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($users)): ?>
          <tr>
            <td colspan="6" style="opacity:.8;">No users found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:14px;">
    <?php
      $prev = max(1, (int)$page - 1);
      $next = min((int)$totalPages, (int)$page + 1);
      $base = "/admin/users?q=" . urlencode((string)$q);
    ?>
    <a class="btn-main" href="<?= $base ?>&page=<?= $prev ?>">Prev</a>
    <span class="badge">Page <?= (int)$page ?> / <?= (int)$totalPages ?></span>
    <a class="btn-main" href="<?= $base ?>&page=<?= $next ?>">Next</a>
  </div>
</section>

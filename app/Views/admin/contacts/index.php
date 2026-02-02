<?php
// Expected vars: $contacts (array), $q, $page, $totalPages, $total, $flashSuccess, $flashError
$q = $q ?? '';
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$total = $total ?? 0;
$contacts = $contacts ?? [];
?>

<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2>Contact Messages</h2>
    <span class="badge"><?= (int)$total ?> total</span>
  </div>

  <?php if (!empty($flashError)): ?><div class="alert error"><?= htmlspecialchars($flashError) ?></div><?php endif; ?>
  <?php if (!empty($flashSuccess)): ?><div class="alert success"><?= htmlspecialchars($flashSuccess) ?></div><?php endif; ?>

  <form method="GET" class="form-grid" style="margin-top:12px; max-width:720px;">
    <div class="form-row">
      <label>Search</label>
      <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="name / email / subject / message...">
    </div>
    <div class="actions">
      <button class="btn-main" type="submit">Search</button>
      <a class="btn-main" href="/admin/contacts">Reset</a>
    </div>
  </form>

  <div class="table-wrap" style="margin-top:14px;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Created</th><th style="width:220px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($contacts as $c): ?>
          <tr>
            <td><?= (int)($c["id"] ?? 0) ?></td>
            <td><?= htmlspecialchars($c["name"] ?? "") ?></td>
            <td><?= htmlspecialchars($c["email"] ?? "") ?></td>
            <td><?= htmlspecialchars($c["subject"] ?? "") ?></td>
            <td><?= htmlspecialchars($c["created_at"] ?? "") ?></td>
            <td>
              <div class="actions">
                <a class="btn-main" href="/admin/contacts/view?id=<?= (int)($c["id"] ?? 0) ?>">View</a>

                <form method="POST" action="/admin/contacts/delete" onsubmit="return confirm('Delete this message?');">
                  <input type="hidden" name="id" value="<?= (int)($c["id"] ?? 0) ?>">
                  <button class="btn-danger" type="submit">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($contacts)): ?>
          <tr>
            <td colspan="6" style="opacity:.8;">No messages found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:14px;">
    <?php
      $prev = max(1, (int)$page - 1);
      $next = min((int)$totalPages, (int)$page + 1);
      $base = "/admin/contacts?q=" . urlencode((string)$q);
    ?>
    <a class="btn-main" href="<?= $base ?>&page=<?= $prev ?>">Prev</a>
    <span class="badge">Page <?= (int)$page ?> / <?= (int)$totalPages ?></span>
    <a class="btn-main" href="<?= $base ?>&page=<?= $next ?>">Next</a>
  </div>
</section>

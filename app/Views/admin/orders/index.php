<?php
// Expected vars: $orders (array), $q, $status, $page, $totalPages, $total, $flashSuccess, $flashError
$q = $q ?? '';
$status = $status ?? '';
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$total = $total ?? 0;
$orders = $orders ?? [];

function badgeClass(string $s): string {
  if ($s === "confirmed") return "badge-user";
  if ($s === "refused") return "badge-admin";
  return "";
}
?>

<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2>Orders</h2>
    <span class="badge"><?= (int)$total ?> total</span>
  </div>

  <?php if (!empty($flashError)): ?><div class="alert error"><?= htmlspecialchars($flashError) ?></div><?php endif; ?>
  <?php if (!empty($flashSuccess)): ?><div class="alert success"><?= htmlspecialchars($flashSuccess) ?></div><?php endif; ?>

  <form method="GET" class="form-grid" style="margin-top:12px; max-width:900px;">
    <div class="form-row">
      <label>Search (id / user_id / name / phone / address)</label>
      <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="ex: 12 or John or +383...">
    </div>

    <div class="form-row">
      <label>Status</label>
      <select name="status">
        <option value="" <?= $status==="" ? "selected" : "" ?>>All</option>
        <option value="pending"   <?= $status==="pending" ? "selected" : "" ?>>pending</option>
        <option value="confirmed" <?= $status==="confirmed" ? "selected" : "" ?>>confirmed</option>
        <option value="refused"   <?= $status==="refused" ? "selected" : "" ?>>refused</option>
      </select>
    </div>

    <div class="actions">
      <button class="btn-main" type="submit">Search</button>
      <a class="btn-main" href="/admin/orders">Reset</a>
    </div>
  </form>

  <div class="table-wrap" style="margin-top:14px;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Customer</th>
          <th>Phone</th>
          <th>Payment</th>
          <th>Status</th>
          <th>Total</th>
          <th>Created</th>
          <th style="width:300px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <?php $st = (string)($o["status"] ?? "pending"); ?>
          <tr>
            <td>#<?= (int)($o["id"] ?? 0) ?></td>
            <td>
              <div style="display:grid; gap:4px;">
                <div class="badge">user_id: <?= (int)($o["user_id"] ?? 0) ?></div>
                <div style="opacity:.85; font-size:13px;"><?= htmlspecialchars($o["user_email"] ?? "") ?></div>
              </div>
            </td>
            <td><?= htmlspecialchars($o["full_name"] ?? "") ?></td>
            <td><?= htmlspecialchars($o["phone"] ?? "") ?></td>
            <td><span class="badge"><?= htmlspecialchars($o["payment_method"] ?? "") ?></span></td>
            <td>
              <span class="badge <?= badgeClass($st) ?>">
                <?= htmlspecialchars($st) ?>
              </span>
            </td>
            <td><?= number_format((float)($o["total"] ?? 0), 2) ?> €</td>
            <td><?= htmlspecialchars($o["created_at"] ?? "") ?></td>
            <td>
              <div class="actions">
                <a class="btn-main" href="/admin/orders/view?id=<?= (int)($o["id"] ?? 0) ?>">View</a>

                <form method="POST" action="/admin/orders/status" style="margin:0; display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                  <input type="hidden" name="id" value="<?= (int)($o["id"] ?? 0) ?>">
                  <select name="status" required>
                    <option value="pending"   <?= $st==="pending" ? "selected" : "" ?>>pending</option>
                    <option value="confirmed" <?= $st==="confirmed" ? "selected" : "" ?>>confirmed</option>
                    <option value="refused"   <?= $st==="refused" ? "selected" : "" ?>>refused</option>
                  </select>
                  <button class="btn-main" type="submit">Update</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($orders)): ?>
          <tr>
            <td colspan="9" style="opacity:.8;">No orders found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:14px;">
    <?php
      $prev = max(1, (int)$page - 1);
      $next = min((int)$totalPages, (int)$page + 1);
      $base = "/admin/orders?q=" . urlencode((string)$q) . "&status=" . urlencode((string)$status);
    ?>
    <a class="btn-main" href="<?= $base ?>&page=<?= $prev ?>">Prev</a>
    <span class="badge">Page <?= (int)$page ?> / <?= (int)$totalPages ?></span>
    <a class="btn-main" href="<?= $base ?>&page=<?= $next ?>">Next</a>
  </div>
</section>

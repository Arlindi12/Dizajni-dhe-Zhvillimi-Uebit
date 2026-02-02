<?php
// Expected vars: $order (array), $items (array), $flashSuccess, $flashError
$order = $order ?? null;
$items = $items ?? [];
if (!$order) {
  echo '<div class="alert error">Order not found.</div>';
  return;
}

function badgeClass(string $s): string {
  if ($s === "confirmed") return "badge-user";
  if ($s === "refused") return "badge-admin";
  return "";
}
$st = (string)($order["status"] ?? "pending");
?>

<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2>Order #<?= (int)($order["id"] ?? 0) ?></h2>
    <a class="btn-main" href="/admin/orders">← Back</a>
  </div>

  <?php if (!empty($flashError)): ?><div class="alert error"><?= htmlspecialchars($flashError) ?></div><?php endif; ?>
  <?php if (!empty($flashSuccess)): ?><div class="alert success"><?= htmlspecialchars($flashSuccess) ?></div><?php endif; ?>

  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:12px; margin-top:12px;">
    <div class="section-card" style="padding:14px;">
      <div style="display:grid; gap:6px;">
        <div><span class="badge">User</span> <strong>#<?= (int)($order["user_id"] ?? 0) ?></strong></div>
        <div style="opacity:.85; font-size:13px;"><?= htmlspecialchars($order["user_email"] ?? "") ?></div>
        <div style="opacity:.8; font-size:14px;"><?= htmlspecialchars($order["created_at"] ?? "") ?></div>
      </div>
    </div>

    <div class="section-card" style="padding:14px;">
      <div style="display:grid; gap:6px;">
        <div><span class="badge">Customer</span> <strong><?= htmlspecialchars($order["full_name"] ?? "") ?></strong></div>
        <div><span class="badge">Phone</span> <?= htmlspecialchars($order["phone"] ?? "") ?></div>
        <div><span class="badge">Payment</span> <?= htmlspecialchars($order["payment_method"] ?? "") ?></div>
      </div>
    </div>

    <div class="section-card" style="padding:14px;">
      <div style="display:grid; gap:8px;">
        <div>
          <span class="badge">Status</span>
          <span class="badge <?= badgeClass($st) ?>"><?= htmlspecialchars($st) ?></span>
        </div>
        <div>
          <span class="badge">Total</span>
          <span class="badge" style="font-size:14px;"><strong><?= number_format((float)($order["total"] ?? 0), 2) ?> €</strong></span>
        </div>

        <form method="POST" action="/admin/orders/status" style="display:flex; gap:8px; flex-wrap:wrap; align-items:center; margin-top:6px;">
          <input type="hidden" name="id" value="<?= (int)($order["id"] ?? 0) ?>">
          <select name="status" required>
            <option value="pending"   <?= $st==="pending" ? "selected" : "" ?>>pending</option>
            <option value="confirmed" <?= $st==="confirmed" ? "selected" : "" ?>>confirmed</option>
            <option value="refused"   <?= $st==="refused" ? "selected" : "" ?>>refused</option>
          </select>
          <button class="btn-main" type="submit">Update status</button>
        </form>
      </div>
    </div>
  </div>

  <div class="section-card" style="padding:14px; margin-top:12px;">
    <h3 style="margin:0 0 10px;">Address</h3>
    <div class="message-text"><?= htmlspecialchars($order["address"] ?? "") ?></div>
  </div>

  <div class="table-wrap" style="margin-top:12px;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Book</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $it): ?>
          <tr>
            <td><?= htmlspecialchars($it["title"] ?? "") ?></td>
            <td><?= number_format((float)($it["price"] ?? 0), 2) ?> €</td>
            <td><?= (int)($it["qty"] ?? 0) ?></td>
            <td><?= number_format((float)($it["subtotal"] ?? 0), 2) ?> €</td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($items)): ?>
          <tr>
            <td colspan="4" style="opacity:.8;">No order items.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

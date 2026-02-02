<?php
// Expected vars: $msg (array)
$msg = $msg ?? null;
if (!$msg) {
  echo '<div class="alert error">Message not found.</div>';
  return;
}
?>

<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2>Message #<?= (int)($msg["id"] ?? 0) ?></h2>
    <div class="actions">
      <a class="btn-main" href="/admin/contacts">← Back</a>

      <form method="POST" action="/admin/contacts/delete" onsubmit="return confirm('Delete this message?');">
        <input type="hidden" name="id" value="<?= (int)($msg["id"] ?? 0) ?>">
        <button class="btn-danger" type="submit">Delete</button>
      </form>
    </div>
  </div>

  <div style="margin-top:12px; display:grid; gap:10px;">
    <div class="section-card" style="padding:14px;">
      <div style="display:grid; gap:6px;">
        <div><span class="badge">From</span> <strong><?= htmlspecialchars($msg["name"] ?? "") ?></strong></div>
        <div><span class="badge">Email</span> <?= htmlspecialchars($msg["email"] ?? "") ?></div>
        <div><span class="badge badge-admin">Subject</span> <strong><?= htmlspecialchars($msg["subject"] ?? "") ?></strong></div>
        <div style="opacity:.7; font-size:14px;">
          <?= htmlspecialchars($msg["created_at"] ?? "") ?>
        </div>
      </div>
    </div>

    <div class="section-card" style="padding:14px;">
      <h3 style="margin:0 0 10px;">Message</h3>
      <div class="message-text"><?= htmlspecialchars($msg["message"] ?? "") ?></div>
    </div>
  </div>
</section>

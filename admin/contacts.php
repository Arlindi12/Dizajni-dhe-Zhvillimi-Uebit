<?php
include __DIR__ . "/../includes/admin_guard.php";
include __DIR__ . "/../includes/flash.php";
include __DIR__ . "/../includes/db.php";

$pageTitle = "Admin | Contacts";
$activePage = "admin";
$requireAuth = true;
include __DIR__ . "/../includes/header.php";

$flashSuccess = flash_get("success");
$flashError = flash_get("error");

$q = trim($_GET["q"] ?? "");

$page = max(1, (int)($_GET["page"] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$total = 0;

if ($q !== "") {
  $like = "%{$q}%";

  $stmtC = $conn->prepare("SELECT COUNT(*) c FROM contacts
                          WHERE name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?");
  $stmtC->bind_param("ssss", $like, $like, $like, $like);
  $stmtC->execute();
  $total = (int)$stmtC->get_result()->fetch_assoc()["c"];
  $stmtC->close();

  $stmt = $conn->prepare("SELECT id,name,email,subject,created_at FROM contacts
                          WHERE name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?
                          ORDER BY id DESC
                          LIMIT ? OFFSET ?");
  $stmt->bind_param("ssssii", $like, $like, $like, $like, $perPage, $offset);
  $stmt->execute();
  $res = $stmt->get_result();
} else {
  $total = (int)$conn->query("SELECT COUNT(*) c FROM contacts")->fetch_assoc()["c"];

  $stmt = $conn->prepare("SELECT id,name,email,subject,created_at
                          FROM contacts
                          ORDER BY id DESC
                          LIMIT ? OFFSET ?");
  $stmt->bind_param("ii", $perPage, $offset);
  $stmt->execute();
  $res = $stmt->get_result();
}

$totalPages = max(1, (int)ceil($total / $perPage));
?>

<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2>Contact Messages</h2>
    <span class="badge"><?= $total ?> total</span>
  </div>

  <?php if ($flashError): ?><div class="alert error"><?= htmlspecialchars($flashError) ?></div><?php endif; ?>
  <?php if ($flashSuccess): ?><div class="alert success"><?= htmlspecialchars($flashSuccess) ?></div><?php endif; ?>

  <form method="GET" class="form-grid" style="margin-top:12px; max-width:720px;">
    <div class="form-row">
      <label>Search</label>
      <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="name / email / subject / message...">
    </div>
    <div class="actions">
      <button class="btn-main" type="submit">Search</button>
      <a class="btn-main" href="contacts.php">Reset</a>
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
        <?php while ($c = $res->fetch_assoc()): ?>
          <tr>
            <td><?= (int)$c["id"] ?></td>
            <td><?= htmlspecialchars($c["name"]) ?></td>
            <td><?= htmlspecialchars($c["email"]) ?></td>
            <td><?= htmlspecialchars($c["subject"]) ?></td>
            <td><?= htmlspecialchars($c["created_at"] ?? "") ?></td>
            <td>
              <div class="actions">
                <a class="btn-main" href="contact_view.php?id=<?= (int)$c["id"] ?>">View</a>
                <form method="POST" action="contact_delete.php" onsubmit="return confirm('Delete this message?');">
                  <input type="hidden" name="id" value="<?= (int)$c["id"] ?>">
                  <button class="btn-danger" type="submit">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:14px;">
    <?php
      $base = "contacts.php?q=" . urlencode($q);
      $prev = max(1, $page - 1);
      $next = min($totalPages, $page + 1);
    ?>
    <a class="btn-main" href="<?= $base ?>&page=<?= $prev ?>">Prev</a>
    <span class="badge">Page <?= $page ?> / <?= $totalPages ?></span>
    <a class="btn-main" href="<?= $base ?>&page=<?= $next ?>">Next</a>
  </div>
</section>

<?php
$stmt->close();
$conn->close();
include __DIR__ . "/../includes/footer.php";
?>

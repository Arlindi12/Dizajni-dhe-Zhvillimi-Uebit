<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2>Admin Dashboard</h2>
    <span class="badge badge-admin">admin</span>
  </div>

  <p style="opacity:.9; margin-top:10px;">
    Welcome, <strong><?= e(Auth::name()) ?></strong>
  </p>

  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px; margin-top:14px;">
    <div class="section-card" style="padding:14px;">
      <div style="opacity:.8;">Users</div>
      <div style="font-size:30px; font-weight:900; margin-top:6px;"><?= (int)$usersCount ?></div>
      <div style="margin-top:10px;">
        <a class="btn-main" href="<?= App::url('/admin/users') ?>">Manage Users</a>
      </div>
    </div>

    <div class="section-card" style="padding:14px;">
      <div style="opacity:.8;">Books</div>
      <div style="font-size:30px; font-weight:900; margin-top:6px;"><?= (int)$booksCount ?></div>
      <div style="margin-top:10px;">
        <a class="btn-main" href="<?= App::url('/admin/books') ?>">Manage Books</a>
      </div>
    </div>

    <div class="section-card" style="padding:14px;">
      <div style="opacity:.8;">Messages</div>
      <div style="font-size:30px; font-weight:900; margin-top:6px;"><?= (int)$contactsCount ?></div>
      <div style="margin-top:10px;">
        <a class="btn-main" href="<?= App::url('/admin/contacts') ?>">View Messages</a>
      </div>
    </div>

    <div class="section-card" style="padding:14px;">
      <div style="opacity:.8;">Orders</div>
      <div style="font-size:30px; font-weight:900; margin-top:6px;"><?= (int)$ordersCount ?></div>
      <div style="margin-top:6px; opacity:.8;">Pending: <strong><?= (int)$pendingCount ?></strong></div>
      <div style="margin-top:10px;">
        <a class="btn-main" href="<?= App::url('/admin/orders') ?>">Manage Orders</a>
      </div>
    </div>
  </div>
</section>

<section class="section-card">
  <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;">
    <h2>Karroca</h2>
    <a class="btn-main" href="<?= App::url('/books') ?>">← Vazhdo me ble</a>
  </div>

  <?php if (empty($items)): ?>
    <div class="alert error" style="margin-top:12px;">Karroca është e zbrazët.</div>
  <?php else: ?>

    <form method="POST" action="<?= App::url('/cart/update') ?>" style="margin-top:12px;">
      <?= csrf_field() ?>
      <div class="table-wrap">
        <table class="admin-table" style="min-width:760px;">
          <thead>
            <tr>
              <th>Libri</th>
              <th>Çmimi</th>
              <th>Sasia</th>
              <th>Nëntotali</th>
              <th>Veprime</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $it): ?>
              <tr>
                <td>
                  <div style="display:flex; gap:12px; align-items:center;">
                    <?php if (!empty($it["image"])): ?>
                      <img class="thumb" src="<?= asset('/' . e($it["image"])) ?>" alt="img">
                    <?php endif; ?>
                    <div>
                      <div style="font-weight:900;"><?= e($it["title"]) ?></div>
                      <a href="<?= App::url('/book-details') ?>?book=<?= urlencode($it["slug"]) ?>" style="opacity:.85;">Hap detajet</a>
                    </div>
                  </div>
                </td>
                <td><?= number_format((float)$it["price"], 2) ?> €</td>
                <td style="max-width:120px;">
                  <input type="number" min="0" name="qty[<?= (int)$it["id"] ?>]" value="<?= (int)$it["qty"] ?>">
                  <div style="opacity:.7; font-size:12px; margin-top:6px;">0 = fshije</div>
                </td>
                <td><?= number_format((float)$it["subtotal"], 2) ?> €</td>
                <td>
                  <form method="POST" action="<?= App::url('/cart/remove') ?>" onsubmit="return confirm('Fshije nga karroca?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="book_id" value="<?= (int)$it["id"] ?>">
                    <button class="btn-danger" type="submit">Remove</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center; margin-top:14px;">
        <div class="actions">
          <button class="btn-main" type="submit">Update quantities</button>
        </div>

        <div class="actions">
          <form method="POST" action="<?= App::url('/cart/clear') ?>"
                onsubmit="return confirm('A je i sigurt qe don me e zbraz karrocen?');"
                style="margin:0;">
            <?= csrf_field() ?>
            <button class="btn-danger" type="submit">Clear cart</button>
          </form>

          <a class="btn-main" href="<?= App::url('/checkout') ?>">Checkout</a>
        </div>

        <div class="badge" style="font-size:14px;">
          Total: <strong><?= number_format((float)$total, 2) ?> €</strong>
        </div>
      </div>
    </form>

  <?php endif; ?>
</section>

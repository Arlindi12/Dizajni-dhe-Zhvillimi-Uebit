<section class="section-card" style="max-width:900px; margin:0 auto;">
  <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
    <h2>Checkout</h2>
    <a class="btn-main" href="<?= App::url('/cart') ?>">← Back to cart</a>
  </div>

  <?php if (!empty($error)): ?><div class="alert error" style="margin-top:12px;"><?= e($error) ?></div><?php endif; ?>

  <?php if (empty($items)): ?>
    <div class="alert error" style="margin-top:12px;">Karroca është e zbrazët. Shto libra para checkout.</div>
    <div class="actions" style="margin-top:12px;">
      <a class="btn-main" href="<?= App::url('/books') ?>">Shko te Librat</a>
    </div>
  <?php else: ?>

    <div class="section-card" style="padding:14px; margin-top:12px;">
      <h3 style="margin:0 0 10px;">Përmbledhje</h3>
      <div style="display:grid; gap:8px;">
        <?php foreach ($items as $it): ?>
          <div style="display:flex; justify-content:space-between; gap:10px;">
            <div style="opacity:.92;">
              <?= e($it["title"]) ?> <span style="opacity:.75;">x<?= (int)$it["qty"] ?></span>
            </div>
            <div style="font-weight:900;"><?= number_format((float)$it["subtotal"], 2) ?> €</div>
          </div>
        <?php endforeach; ?>
        <div style="border-top:1px solid rgba(255,255,255,0.10); padding-top:10px; display:flex; justify-content:space-between;">
          <div class="badge">Total</div>
          <div class="badge" style="font-size:14px;"><strong><?= number_format((float)$total, 2) ?> €</strong></div>
        </div>
      </div>
    </div>

    <form method="POST" class="form-grid" style="margin-top:12px;">
      <?= csrf_field() ?>

      <div class="form-row">
        <label>Emri dhe Mbiemri</label>
        <input name="full_name" required value="<?= e($_POST["full_name"] ?? "") ?>">
      </div>

      <div class="form-row">
        <label>Telefoni</label>
        <input name="phone" required value="<?= e($_POST["phone"] ?? "") ?>">
      </div>

      <div class="form-row">
        <label>Adresa</label>
        <textarea name="address" rows="4" required><?= e($_POST["address"] ?? "") ?></textarea>
      </div>

      <div class="form-row">
        <label>Mënyra e pagesës</label>
        <select name="payment" required>
          <option value="cash" <?= (($_POST["payment"] ?? "cash") === "cash") ? "selected" : "" ?>>Cash on delivery</option>
          <option value="card" <?= (($_POST["payment"] ?? "") === "card") ? "selected" : "" ?>>Card (demo)</option>
        </select>
      </div>

      <div class="actions" style="margin-top:6px;">
        <button class="btn-main" type="submit">Krijo porosinë (Pending)</button>
        <a class="btn-danger" href="<?= App::url('/cart') ?>">Cancel</a>
      </div>
    </form>

  <?php endif; ?>
</section>

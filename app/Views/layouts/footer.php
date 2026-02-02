<?php if (empty($useAuthCss)): ?>
</main>

<footer class="footer">
  <div class="footer-inner">
    <div class="footer-col">
      <h3>Rreth Nesh</h3>
      <p>BookNest është libraria juaj online me librat më të mirë për çdo moshë.</p>
    </div>

    <div class="footer-col">
      <h3>Quick Links</h3>
      <ul>
        <li><a href="<?= App::url('/') ?>">Home</a></li>
        <li><a href="<?= App::url('/books') ?>">Librat</a></li>
        <li><a href="<?= App::url('/contact') ?>">Kontakt</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h3>Na ndiqni</h3>
      <p>
        <a href="#">Facebook</a> ·
        <a href="#">Instagram</a> ·
        <a href="#">Twitter</a>
      </p>
    </div>
  </div>

  <div class="footer-bottom">
    &copy; <?= date("Y"); ?> BookNest · Të gjitha të drejtat e rezervuara
  </div>
</footer>
<?php endif; ?>

</body>
</html>

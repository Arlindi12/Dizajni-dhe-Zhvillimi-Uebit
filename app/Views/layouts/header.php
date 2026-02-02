<?php
$pageTitle = $pageTitle ?? "BookNest";
$activePage = $activePage ?? "";
$useAuthCss = $useAuthCss ?? false;

$appName = App::cfg('app_name', 'BookNest');
$cartQty = cart_count();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($pageTitle) ?></title>

  <link rel="stylesheet" href="<?= asset('/css/style.css') ?>" />
  <?php if ($useAuthCss): ?>
    <link rel="stylesheet" href="<?= asset('/css/auth.css') ?>" />
  <?php endif; ?>

  <script src="<?= asset('/js/script.js') ?>" defer></script>

  <style>
    .cart-link{ position: relative; display: inline-flex; align-items: center; gap: 6px; }
    .cart-badge{
      position:absolute; top:-6px; right:-10px;
      min-width:18px; height:18px; padding:0 6px;
      border-radius:999px;
      background: linear-gradient(135deg, #22c55e, #16a34a);
      color:#fff; font-size:11px; font-weight:900;
      display:flex; align-items:center; justify-content:center;
      box-shadow: 0 4px 12px rgba(34,197,94,0.55);
      border: 1px solid rgba(255,255,255,0.55);
      line-height: 1;
    }
  </style>
</head>

<body class="<?= e($useAuthCss ? "auth-page" : "app-page") ?>">

<?php if (!$useAuthCss): ?>
<header class="topbar">
  <div class="topbar-inner">

    <a class="brand" href="<?= App::url('/') ?>">
      <span class="brand-dot"></span>
      <span class="brand-text"><?= e($appName) ?></span>
    </a>

    <button class="nav-toggle" id="navToggle" aria-label="Menu">☰</button>

    <nav class="nav" id="navMenu">
      <a href="<?= App::url('/') ?>" class="<?= ($activePage==="home") ? "active" : "" ?>">Home</a>
      <a href="<?= App::url('/books') ?>" class="<?= ($activePage==="books") ? "active" : "" ?>">Librat</a>

      <a href="<?= App::url('/cart') ?>" class="cart-link <?= ($activePage==="cart") ? "active" : "" ?>">
        Karroca
        <?php if ($cartQty > 0): ?>
          <span class="cart-badge"><?= (int)$cartQty ?></span>
        <?php endif; ?>
      </a>

      <a href="<?= App::url('/my-orders') ?>" class="<?= ($activePage==="orders") ? "active" : "" ?>">
        Porositë e mia
      </a>

      <a href="<?= App::url('/contact') ?>" class="<?= ($activePage==="contact") ? "active" : "" ?>">Kontakt</a>

      <?php if (Auth::isAdmin()): ?>
        <a href="<?= App::url('/admin/dashboard') ?>" class="<?= ($activePage==="admin") ? "active" : "" ?>">Admin</a>
      <?php endif; ?>
    </nav>

    <div class="nav-user">
      <span class="user-pill">
        <span class="user-icon" aria-hidden="true"></span>
        <?= e(Auth::name()) ?>
      </span>
      <a class="btn-danger" href="<?= App::url('/logout') ?>">Logout</a>
    </div>

  </div>
</header>

<main class="app-main">
<?php endif; ?>

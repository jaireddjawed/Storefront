<?php
  $currentDirectory = __DIR__;

  require_once($currentDirectory . '/../../includes/EnvSetup.php');
  require_once($currentDirectory . '/../../includes/ActiveRoute.php');
  require_once($currentDirectory . '/../../includes/DBConnection.php');
  require_once($currentDirectory . '/../../includes/LoginFunctions.php');

  $productName = getenv('PRODUCT_NAME');

  $connection = createDBConnection();
  $isLoggedIn = checkAuthStatus($connection);
  $role = checkUserRole($connection);
  $connection->close();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/"><?= $productName ?></a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <?php if ($isLoggedIn) : ?>
      <ul class="navbar-nav mr-auto">
        <li class="nav-item <?= activeRoute('home') ?>">
          <a href="/pages/home" class="nav-link">Home</a>
        </li>
        <li class="nav-item <?= activeRoute('catalog') ?>">
          <a href="/pages/catalog" class="nav-link">Catalog</a>
        </li>
        <li class="nav-item <?= activeRoute('cart') ?>">
          <a href="/pages/cart" class="nav-link">Cart</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <?php if ($role == 'admin') : ?>
          <li class="nav-item <?= activeRoute('list-products') ?>">
            <a href="/pages/list-products" class="nav-link">Your Products</a>
          </li>
          <li class="nav-item <?= activeRoute('list-orders') ?>">
            <a href="/pages/list-orders" class="nav-link">Customer Orders</a>
          </li>
        <?php endif ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= $_SESSION['full_name'] ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="/pages/logout">Log Out</a>
          </div>
        </li>
      </ul>
    <?php else : ?>
      <ul class="navbar-nav mr-auto">
        <li class="nav-item <?= activeRoute('home') ?>">
          <a href="/pages/home" class="nav-link">Home</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item <?= activeRoute('login'); ?>">
          <a href="/pages/login" class="nav-link">Login</a>
        </li>
        <li class="nav-item <?= activeRoute('signup'); ?>">
          <a href="/pages/signup" class="nav-link">Signup</a>
        </li>
      </ul>
    <?php endif ?>
  </div>
</nav>

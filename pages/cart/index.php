<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/PageTemplate.php');
  require_once('../../includes/LoginFunctions.php');
  require_once('./cart.php');

  if (!isset($template))
  {
    $template = new PageTemplate();
    $template->pagetitle = 'Cart';
    $template->body = __FILE__;

    require_once('../../layouts/App/index.php');
    exit;
  }

  $userId = $_SESSION['user_id'];

  $connection = createDBConnection();
  $isLoggedIn = checkAuthStatus($connection);
  $cartItems = getCartItems($connection, $userId);

  $cartTotal = 0;
?>

<h1 class="page-header">Cart</h1>
<?php if ($isLoggedIn) : ?>
  <div class="row">
    <div class="col-xs-12 col-lg-8">
      <?php foreach ($cartItems as $cartItem) : ?>
        <?php $product = getProduct($connection, $cartItem['product_id']); ?>
        <?php $price = getSkuPrice($connection, $cartItem['product_id'], $cartItem['sku_id']) ?>
        <?php $cartTotal += $price ?>
        <div class="card">
          <img src="<?= $product['images'][0] ?>" class="card-img-top" style="max-width:100%;" />
          <div class="card-body">
            <h4><?= $product['title'] ?></h4>
            <p class="text-success">$<?= $price / 100 ?></p>
          </div>
        </div>
      <?php endforeach ?>
    </div>
    <div class="col-xs-12 col-lg-4">
      <a href="/pages/checkout" class="btn btn-primary btn-block">Checkout $<?= $cartTotal / 100 ?></a>
    </div>
  </div>
<?php else : ?>
  <div class="alert alert-info text-center">You must be logged in to see your cart.</div>
<?php endif ?>

<?php
  $connection->close();
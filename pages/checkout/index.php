<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/PageTemplate.php');
  require_once('../../includes/CsrfToken.php');
  require_once('../../includes/LoginFunctions.php');

  $currentDirectory = '/pages/' . basename(__DIR__);

  if (!isset($template))
  {
    $template = new PageTemplate();
    $template->pagetitle = 'Checkout';
    $template->body = __FILE__;

    require_once('../../layouts/App/index.php');
    exit;
  }

  $connection = createDBConnection();
  $isLoggedIn = checkAuthStatus($connection);
  $connection->close();
?>

<h3 class="page-header">Checkout</h3>
<?php if ($isLoggedIn) : ?>
  <form action="<?= $currentDirectory . '/handle-checkout.php' ?>" method="POST">
    <?php require_once('../../components/AddressForm/index.html') ?>
    <?php require_once('../../components/CreditCardForm/index.html') ?>
    <input type="hidden" name="csrf-token" value="<?= $_SESSION['csrf_token'] ?>" />
    <div class="form-group">
      <button type="submit" class="btn btn-primary btn-block">Checkout</button>
    </div>
  </form>
<?php else : ?>
  <div class="alert alert-info text-center">Please log in to checkout.</div>
<?php endif ?>

<script src="/assets/js/handle-checkout.js"></script>

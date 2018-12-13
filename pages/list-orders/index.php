<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/PageTemplate.php');
  require_once('../../includes/LoginFunctions.php');
  require_once('./list-orders.php');

  if (!isset($template))
  {
    $template = new PageTemplate();
    $template->pagetitle = 'List Orders';
    $template->body = __FILE__;

    require_once('../../layouts/App/index.php');
    exit;
  }

  $connection = createDBConnection();
  $isLoggedIn = checkAuthStatus($connection);
  $orders = listOrders($connection);
  $connection->close();
?>

<h4 class="page-header">List Orders</h4>

<?php if ($isLoggedIn) : ?>
  <table class="table">
    <thead>
      <tr>
        <th>Order Id</th>
        <th>Street Address</th>
        <th>Street Address Line 2</th>
        <th>City</th>
        <th>State</th>
        <th>Postal Code</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($orders as $order) : ?>
        <tr>
          <th scope="row"><a href="/pages/order/?orderId=<?= $order['order_id'] ?>"><?= $order['order_id'] ?></a></th>
          <th><?= $order['street_address'] ?></th>
          <th><?= $order['street_address_2'] ?></th>
          <th><?= $order['city'] ?></th>
          <th><?= $order['state'] ?></th>
          <th><?= $order['postal_code'] ?></th>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php else : ?>
  <div class="alert alert-info text-center">Log in to view orders.</div>
<?php endif ?>

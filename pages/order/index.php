<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/PageTemplate.php');
  require_once('./order.php');

  if (!isset($template))
  {
    $template = new PageTemplate();
    $template->pagetitle = 'Customer Order';
    $template->body = __FILE__;

    require_once('../../layouts/App/index.php');
    exit;
  }

  $connection = createDBConnection();
  $orderId = $_GET['orderId'];
  $order = getOrder($connection, $orderId);
  $orderItems = listOrderItems($connection, $orderId);
?>

<h4 class="page-header">Customer Order </h4>
<div class="row">
  <div class="col-xs-12 col-lg-8">
    <?php foreach ($orderItems as $orderItem) : ?>
        <?php
          $productId = $orderItem['product_id'];
          $skuId = $orderItem['sku_id'];

          $product = getProduct($connection, $productId);
          $sku = getSku($connection, $productId, $skuId);
        ?>

        <div class="card">
          <img src="<?= $product['images'][0] ?>" class="card-img-top" />
          <div class="card-body">
            <h4><?= $product['title'] ?></h4>
            <p class="text-success">$<?= $sku['price'] / 100 ?></p>
            <ul>
              <li><?= $sku['attribute_1'] ?></li>
              <li><?= $sku['attribute_2'] ?></li>
              <li><?= $sku['attribute_3'] ?></li>
            </ul>
          </div>
        </div>
    <?php endforeach ?>
  </div>
  <div class="col-xs-12 col-lg-4">
    <h6>Address</h6>
    <p>Street Address: <?= $order['street_address'] ?></p>
    <p>Street Address Line 2: <?= $order['street_address_2'] ?></p>
    <p>City: <?= $order['city'] ?></p>
    <p>State: <?= $order['state'] ?></p>
    <p>Postal Code: <?= $order['postal_code'] ?></p>
  </div>
</div>

<?php
  $connection->close();

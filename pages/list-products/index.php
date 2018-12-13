<?php
  require_once('../../includes/PageTemplate.php');
  require_once('../../includes/DBConnection.php');
  require_once('./list-products.php');

  $currentDirectory = '/pages/' . basename(__DIR__);

  if (!isset($template))
  {
    $template = new PageTemplate();
    $template->pagetitle = "Your Products";
    $template->body = __FILE__;

    require('../../layouts/App/index.php');
    exit;
  }

  $connection = createDBConnection();
  $products = listProducts($connection);
  $connection->close();
?>

<h3 class="page-header">Your Products</h3>
<div class="row">
  <div class="col-xs-12 col-md-8">
    <table class="table">
      <thead>
        <tr>
          <th>Product Id</th>
          <th>Title</th>
          <th>Date Created</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product) : ?>
          <tr>
            <th scope="row"><?= $product['product_id'] ?></th>
            <th><a href="/pages/product-info/?productId=<?= $product['product_id'] ?>"<p><?= $product['title'] ?></p><a/></th>
            <th><?= $product['created_at'] ?></th>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <div class="col-xs-12 col-md-4">
    <a href="/pages/create-product" class="btn btn-success btn-block"><i class="fa fa-plus-circle"></i> Add Product</a>
  </div>
</div>

<?php
  $products->close();

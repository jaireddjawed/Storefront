<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/PageTemplate.php');
  require_once('../../includes/CsrfToken.php');
  require_once('./handle-catalog.php');

  if (!isset($template))
  {
    $template = new PageTemplate();
    $template->pagetitle = 'View Product';
    $template->body = __FILE__;

    require_once('../../layouts/App/index.php');
    exit;
  }

  $connection = createDBConnection();
  $products = listProducts($connection);
?>

<h1 class="page-header">Catalog</h1>
<div class="row">
  <?php foreach ($products as $product) : ?>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <div class="card h-100">
        <img src="<?= explode(', ', $product['images'])[0] ?>" style="max-width: 100%;" />
        <div class="card-body">
          <h4 class="card-title">
            <a href="/pages/view-product/?productId=<?= $product['product_id'] ?>">
              <?= $product['title'] ?>
            </a>
          </h4>
        </div>
      </div>
    </div>
  <?php endforeach ?>
</div>

<?php
  require_once('../../includes/PageTemplate.php');
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/CsrfToken.php');
  require_once('./retrieve-product.php');

  $currentDirectory = '/pages/' . basename(__DIR__);

  $connection = createDBConnection();
  $productId = $_GET['productId'];
  $product = Product::getProductById($connection, $productId);
  $connection->close();

  if (!isset($template))
  {
    $template = new PageTemplate();
    $template->pagetitle = "Product #" . $productId;
    $template->body = __FILE__;

    require('../../layouts/App/index.php');
    exit;
  }
?>

<?php if ($product) : ?>
  <h3 class="page-header"><?= $product['title'] ?></h3>
  <div class="row">
    <div class="col-xs-12 col-md-6">
      <div class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <?php for ($i = 0; $i < count($product['images']); $i++) : ?>
            <div class="carousel-item <?= $i == 0 ? 'active' : '' ?>">
              <img class="d-block w-100" src="<?= $product['images'][$i] ?>" />
            </div>
          <?php endfor ?>
        </div>
      </div>
      <p class="form-text text-muted text-center"><?= $product['caption'] ?></p>
    </div>
    <div class="col-xs-12 col-md-6">
      <div class="form-group">
        <label class="control-label">Description</label>
        <p><?= $product['description'] ?></p>
      </div>
      <div class="form-group">
        <label class="control-label">Attributes</label>
        <ul>
          <?php foreach($product['attributes'] as $attribute) : ?>
            <li><?= $attribute ?></li>
          <?php endforeach ?>
        </ul>
      </div>
      <form action="<?= $currentDirectory . '/retrieve-product.php' ?>" method="POST">
        <input type="hidden" name="product-id" value="<?= $productId ?>" />
        <input type="hidden" name="csrf-token" value="<?= $_SESSION['csrf_token'] ?>" />
        <div class="form-group">
          <a href="/pages/create-sku/?productId=<?= $productId ?>" class="btn btn-success btn-block">Create Sku</a>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-danger btn-block">Remove Product</button>
        </div>
      </form>
    </div>
  </div>
<?php else : ?>
  <div class="form-group">
    <div class="alert alert-info text-center">This Product Does Not Exist.</div>
  </div>
<?php endif ?>

<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/PageTemplate.php');
  require_once('../../includes/CsrfToken.php');
  require_once('../../includes/LoginFunctions.php');
  require_once('./handle-view-product.php');

  $currentDirectory = '/pages/' . basename(__DIR__);

  if (!isset($template))
  {
    $template = new PageTemplate();
    $template->pagetitle = 'View Product';
    $template->body = __FILE__;

    require_once('../../layouts/App/index.php');
    exit;
  }

  $connection = createDBConnection();
  $productId = $_GET['productId'];

  $product = Product::retrieveProduct($productId, $connection);
  $skus = Product::retrieveSkus($productId, $connection);

  $isLoggedIn = checkAuthStatus($connection);
  $connection->close();
?>

<?php if ($product) : ?>
  <?php if ($isLoggedIn) : ?>
    <h1 class="page-header"><?= $product['title'] ?></h1>
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
          <label>Description</label>
          <p><?= $product['description'] ?></p>
        </div>
        <form action="<?= $currentDirectory . '/handle-view-product.php' ?>" method="POST">
          <?php if (count($product['attributes']) > 0) : ?>
            <?php for ($i = 0; $i < count($product['attributes']); $i++) : ?>
              <div class="form-group">
                <label class="control-label"><?= $product['attributes'][$i] ?></label>
                <select name="attribute-<?= $i ?>" class="form-control attribute" required>
                  <?php foreach ($skus as $sku) : ?>
                    <?php $incrementedAttr = $i + 1; ?>
                    <option><?= $sku['attribute_' . $incrementedAttr] ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            <?php endfor ?>
          <?php endif ?>
          <div class="form-group">
            <label class="control-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" />
          </div>
          <p id="price-div" class="text-success"></p>
          <input type="hidden" name="product-id" value="<?= $productId ?>" />
          <input type="hidden" name="csrf-token" value="<?= $_SESSION['csrf_token'] ?>" />
          <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-plus-circle"></i> Add To Cart</button>
        </form>
      </div>
    </div>
  <?php else : ?>
    <div class="alert alert-info text-center">You must be logged in to view products.</div>
  <?php endif ?>
<?php else : ?>
  <div class="alert alert-info text-center">This product does not exist.</div>
<?php endif ?>

<script src="/assets/js/view-product.js"></script>

<?php
  $skus->close();

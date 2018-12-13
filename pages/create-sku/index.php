<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/PageTemplate.php');
  require_once('../../includes/CsrfToken.php');
  require_once('./handle-sku-creation.php');

  $currentDirectory = '/pages/' . basename(__DIR__);

  $connection = createDBConnection();
  $productId = $_GET['productId'];

  $attributes = Sku::listAttributes($connection, $productId);
  $connection->close();

  if (!isset($template))
  {
    $template = new PageTemplate();
    $template->pagetitle = 'Create SKU';
    $template->body = __FILE__;

    require_once('../../layouts/App/index.php');
    exit;
  }
?>

<div class="row">
  <div class="col-xs-12 col-lg-6">
    <h3 class="page-header">Create Sku</h3>
    <form action="<?= $currentDirectory . '/handle-sku-creation.php' ?>" method="POST">
      <?php for ($i = 0; $i < count($attributes); $i++) : ?>
        <div class="form-group">
          <label><?= $attributes[$i] ?></label>
          <input name="attribute-<?= $i ?>" placeholder="Option" class="form-control" required />
        </div>
      <?php endfor ?>

      <div class="form-group">
        <label class="control-label">Price</label>
        <input type="tel" name="price" placeholder="19.99" class="form-control" required />
      </div>

      <div class="form-group">
        <label class="control-label">Inventory</label>
        <select name="inventory" class="form-control">
          <option value="finite">Finite</option>
          <option value="infinite">Infinite</option>
        </select>
      </div>

      <div id="quantity" class="form-group">
        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control" />
      </div>

      <input type="hidden" name="product-id" value="<?= $productId ?>" />
      <input type="hidden" name="csrf-token" value="<?= $_SESSION['csrf_token'] ?>" />

      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block">Add Sku</button>
      </div>
    </form>
  </div>
</div>

<script src="/assets/js/handle-create-sku.js"></script>

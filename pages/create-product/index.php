<?php
  require_once('../../includes/PageTemplate.php');
  require_once('../../includes/CsrfToken.php');

  $currentDirectory = '/pages/' . basename(__DIR__);

  if (!isset($template))
  {
    $template = new PageTemplate();
    $template->pagetitle = 'Create Product';
    $template->body = __FILE__;

    require_once('../../layouts/App/index.php');
    exit;
  }
?>

<div class="row">
  <div class="col-xs-12 col-lg-6">
    <h3 class="page-header">Create Product</h3>
    <form enctype="multipart/form-data" action="<?= $currentDirectory . '/handle-create-product.php' ?>" method="POST">
      <div class="form-group">
        <label class="control-label">Title</label>
        <input type="text" name="title" class="form-control" placeholder="Awesome Blue T-Shirt" required />
      </div>
      <div class="form-group">
        <label class="control-label">Caption</label>
        <input type="text" name="caption" placeholder="Comfortable blue cotton t-shirt" class="form-control" />
        <p class="form-text text-muted">A one-line, simple description of the product.</p>
      </div>
      <div class="form-group">
        <label class="control-label">Description</label>
        <textarea name="description" class="form-control" rows="6"></textarea>
      </div>
      <div class="form-group">
        <label class="control-label">Attributes</label>
        <input type="text" name="attributes" placeholder="Size, Style, Color" class="form-control" />
      </div>
      <div class="form-group">
        <label class="control-label">Images</label>
        <input type="file" name="images[]" multiple="multiple" required />
      </div>

      <div class="form-group">
        <input type="checkbox" name="shippable" value="shippable" checked />
        <label>This product is shippable.</label>
      </div>

      <?php require_once('../../components/ProductStats/index.html'); ?>

      <input type="hidden" name="csrf-token" value="<?= $_SESSION['csrf_token'] ?>" />
      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block">Create Product</button>
      </div>
    </form>
  </div>
</div>

<script src="/assets/js/regex-validation.js"></script>
<script src="/assets/js/handle-create-product.js"></script>

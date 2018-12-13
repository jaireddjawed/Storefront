<?php
  require_once('../../includes/PageTemplate.php');

  if (!isset($template))
  {
    $template = new PageTemplate();
    $template->pagetitle = 'Checkout';
    $template->body = __FILE__;

    require_once('../../layouts/App/index.php');
    exit;
  }
?>

<h3 class="page-header">Order Success!</h3>
<p>Your Order has been successfully sent! It will be fulfilled shortly.</p>
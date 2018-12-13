<?php
  $layoutDirectory = __DIR__;
  require_once($layoutDirectory . '/../../includes/EnvSetup.php');

  $productName = getenv('PRODUCT_NAME');
  $stripePublishableKey = getenv('STRIPE_KEY');
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title><?= $template->pagetitle . ' | ' . $productName ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://getbootstrap.com/docs/4.1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- JS -->
    <script src="https://js.stripe.com/v2/"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js"></script>
    <script src="/assets/js/regex-validation.js"></script>
    <script>
      // Set Publishable Key for Stripe
      Stripe.setPublishableKey('<?= $stripePublishableKey ?>');
    </script>
  </head>
  <body>
    <?php
    require_once($layoutDirectory . '/../../components/Navigation/index.php'); ?>
    <div class="container">
      <?php
        if (isset($template->body))
        {
          require($template->body);
        }
      ?>
    </div>
    <?php
    require_once($layoutDirectory . '/../../components/Footer/index.php'); ?>
  </body>
</html>

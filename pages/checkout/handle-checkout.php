<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/CsrfToken.php');
  require_once('../../includes/Stripe.php');

  class HandleCheckout
  {
    private $connection;
    private $stripeToken;

    private $streetAddress;
    private $streetAddress2;
    private $city;
    private $state;
    private $postalCode;

    public function __construct($streetAddress, $streetAddress2, $city, $state, $postalCode)
    {
      $this->streetAddress = $streetAddress;
      $this->streetAddress2 = $streetAddress2;
      $this->city = $city;
      $this->state = $state;
      $this->postalCode = $postalCode;

      $this->connection = createDBConnection();
    }

    public function __destruct()
    {
      $this->connection->close();
    }

    public function setStripeToken($stripeToken)
    {
      $this->stripeToken = $stripeToken;
    }

    public function getCartItemTotal()
    {
      $userId = $_SESSION['user_id'];
      $total = 0;

      $cartStmt = 'SELECT `sku_id` FROM `cart_items` WHERE `user_id` = "'.$userId.'"';
      $cartItems = $this->connection->query($cartStmt);

      foreach ($cartItems as $cartItem) {
        $total += $this->getPriceFromSkuId($cartItem['sku_id']);
      }

      return $total;
    }

    public function getPriceFromSkuId($skuId)
    {
      $skuStmt = 'SELECT `price` FROM `skus` WHERE `sku_id` = ? LIMIT 1';
      $skuStmt = $this->connection->prepare($skuStmt);
      $skuStmt->bind_param('s', $skuId);
      $skuStmt->execute();
      $skuStmt->store_result();

      $skuStmt->bind_result($price);
      $skuStmt->fetch();

      return $price;
    }

    public function saveOrderInDatabase()
    {
      $orderId = uniqid();
      $userId = $_SESSION['user_id'];

      $orderStmt = 'INSERT INTO `orders` (order_id, user_id, street_address, street_address_2, city, state, postal_code)
        VALUES ("'.$orderId.'", "'.$userId.'",
        "'.$this->streetAddress.'", "'.$this->streetAddress2.'", "'.$this->city.'", "'.$this->state.'", "'.$this->postalCode.'")';
      $orderStmt = $this->connection->query($orderStmt);

      return $orderId;
    }

    public function retrieveCartQty($userId, $skuId) {
      $cartStmt = 'SELECT `quantity` FROM `cart_items` WHERE `user_id` = "'.$userId.'" AND `sku_id` = "'.$skuId.'" LIMIT 1';
      $cartStmt = $this->connection->query($cartStmt);
      $cart = $cartStmt->fetch_row();
      return $cart[0];
    }

    public function retrieveSkuQty($skuId) {
      $skuStmt = 'SELECT `quantity` FROM `skus` WHERE `sku_id` = "'.$skuId.'" LIMIT 1';
      $skuStmt = $this->connection->query($skuStmt);
      $sku = $skuStmt->fetch_row();
      return $sku[0];
    }

    public function saveOrderItemsInDatabaseAndAdjustStockQuantity($orderId)
    {
      $userId = $_SESSION['user_id'];

      $cartStmt = 'SELECT `product_id`, `sku_id` FROM `cart_items` WHERE `user_id` = "'.$userId.'"';
      $cartItems = $this->connection->query($cartStmt);

      foreach ($cartItems as $cartItem) {
        $currentSkuQuantity = $this->retrieveSkuQty($cartItem['sku_id']);
        $cartQty = $this->retrieveCartQty($userId, $cartItem['sku_id']);
        $currentSkuQuantity -= $cartQty;

        if ($currentSkuQuantity != null) {
          $skuStmt = 'UPDATE `skus` SET `quantity` = "'.$currentSkuQuantity.'" WHERE `sku_id` = "'.$cartItem['sku_id'].'" ';
          $skuStmt = $this->connection->query($skuStmt);
        }

        $orderItemStmt = 'INSERT INTO `order_items` (order_id, product_id, sku_id) VALUES (?, ?, ?)';
        $orderItemStmt = $this->connection->prepare($orderItemStmt);
        $orderItemStmt->bind_param('sss', $orderId, $cartItem['product_id'], $cartItem['sku_id']);
        if (!$orderItemStmt->execute()) {
          echo('Error saving order');
          exit;
        }
      }
    }

    public function removeCartItems()
    {
      $userId = $_SESSION['user_id'];
      $cartStmt = 'DELETE FROM `cart_items` WHERE `user_id` = "'.$userId.'"';
      $cartStmt = $this->connection->query($cartStmt);
    }

    public function chargeUserCard()
    {
      $chargeTotal = $this->getCartItemTotal();
      $charge = chargeCardFromStripeToken([
        'amount' => $chargeTotal,
        'currency' => 'usd',
        'source' => $this->stripeToken,
      ]);
      return $charge;
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    $csrfFormToken = filter_input(INPUT_POST, 'csrf-token', FILTER_SANITIZE_STRING);
    $sessionCsrfToken = $_SESSION['csrf_token'];

    // Verify the CSRF Token is correct
    if ($csrfFormToken != $sessionCsrfToken)
    {
      echo('Invalid Csrf Token!');
      exit;
    }

    // Stripe Token
    $stripeToken = filter_input(INPUT_POST, 'stripe-token', FILTER_SANITIZE_STRING);

    // User's Shipping address
    $streetAddress = filter_input(INPUT_POST, 'street-address', FILTER_SANITIZE_STRING);
    $streetAddress2 = filter_input(INPUT_POST, 'street-address-line-2', FILTER_SANITIZE_STRING);

    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
    $postalCode = filter_input(INPUT_POST, 'postal-code', FILTER_SANITIZE_STRING);

    $checkout = new HandleCheckout($streetAddress, $streetAddress2, $city, $state, $postalCode);
    $checkout->setStripeToken($stripeToken);
    $checkout->chargeUserCard();

    $orderId = $checkout->saveOrderInDatabase();
    $checkout->saveOrderItemsInDatabaseAndAdjustStockQuantity($orderId);
    $checkout->removeCartItems();

    header('Location: ../order-success');
    exit;
  }

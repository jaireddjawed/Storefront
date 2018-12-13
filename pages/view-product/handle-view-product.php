<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/CsrfToken.php');

  class Product {
    private $productId;
    private $connection;
    private $quantity;

    private $attr1;
    private $attr2;
    private $attr3;

    public function __construct($productId, $quantity) {
      $this->productId = $productId;
      $this->quantity = $quantity;
      $this->connection = createDBConnection();
    }

    public function __descruct() {
      $this->connection->close();
    }

    public function setAttributes ($attr1, $attr2, $attr3) {
      $this->attr1 = $attr1 != '' ? $attr1 : null;
      $this->attr2 = $attr2 != '' ? $attr2 : null;
      $this->attr3 = $attr3 != '' ? $attr3 : null;
    }

    public function checkIfAttrIsDefined($attr) {
      return $attr != null ? '= "' . $attr . '"' : 'IS NULL';
    }

    public function retrieveSkuIdFromAttributes() {
      $isAttr1Defined = $this->checkIfAttrIsDefined($this->attr1);
      $isAttr2Defined = $this->checkIfAttrIsDefined($this->attr2);
      $isAttr3Defined = $this->checkIfAttrIsDefined($this->attr3);

      $skuStmt = 'SELECT `sku_id`, `quantity` FROM `skus` WHERE
        `product_id` = "'.$this->productId.'" AND `attribute_1` '.$isAttr1Defined.' AND `attribute_2` '.$isAttr2Defined.' AND `attribute_3` '.$isAttr3Defined.' LIMIT 1';
      $skuStmt = $this->connection->query($skuStmt);
      $sku = $skuStmt->fetch_row();

      $skuId = $sku[0];
      $quantity = $sku[1];

      // check if the quantity the user entered is more than the amount in stock
      if ($quantity != null && $quantity < $this->quantity) {
        echo('The quantity entered exceeds the amount in stock.');
        exit;
      }

      return $skuId;
    }

    public function addItemToCart() {
      $userId = $_SESSION['user_id'];
      $skuId = $this->retrieveSkuIdFromAttributes();

      $skuStmt = "SELECT `sku_id` FROM `cart_items` WHERE `user_id` = '".$userId."' AND `sku_id` = '".$skuId."' LIMIT 1";
      $skuStmt = $this->connection->query($skuStmt);

      if ($skuStmt->fetch_row()) {
        echo('This cart item already exists.');
        exit;
      }

      $cartStmt = 'INSERT INTO `cart_items` (product_id, sku_id, quantity, user_id) VALUES (?, ?, ?, ?)';
      $cartStmt = $this->connection->prepare($cartStmt);
      $cartStmt->bind_param('ssss', $this->productId, $skuId, $this->quantity, $userId);

      if (!$cartStmt->execute()) {
        echo('Could not save item in cart!');
        exit;
      }
    }

    public static function retrieveProduct($productId, $connection) {
      $productStmt = 'SELECT `title`, `caption`, `description`, `attributes`, `images` FROM `products` WHERE `product_id` = ?';
      $productStmt = $connection->prepare($productStmt);
      $productStmt->bind_param('s', $productId);
      $productStmt->execute();
      $productStmt->store_result();

      $productStmt->bind_result($title, $caption, $description, $attributes, $images);
      $productStmt->fetch();

      if ($productStmt->num_rows === 1)
      {
        $productStmt->close();
        return [
          'title' => $title,
          'caption' => $caption,
          'description' => $description,
          'attributes' => explode(', ', $attributes),
          'images' => explode(', ', $images)
        ];
      }

      return null;
    }

    public static function retrieveSkus($productId, $connection) {
      $skuStmt = 'SELECT `attribute_1`, `attribute_2`, `attribute_3` `price`, `quantity` FROM `skus` WHERE `product_id` = "'.$productId.'"';
      return $skuStmt = $connection->query($skuStmt);
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

    // Rules for recieving integer input
    $options = [
      'default' => 0,
      'min_range' => 0,
      'max_range' => 200
    ];

    $productId = filter_input(INPUT_POST, 'product-id', FILTER_SANITIZE_STRING);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT, $options);

    // Get the product attributes
    $attr1 = filter_input(INPUT_POST, 'attribute-0', FILTER_SANITIZE_STRING);
    $attr2 = filter_input(INPUT_POST, 'attribute-1', FILTER_SANITIZE_STRING);
    $attr3 = filter_input(INPUT_POST, 'attribute-2', FILTER_SANITIZE_STRING);

    // Save Product to cart
    $product = new Product($productId, $quantity);
    $product->setAttributes($attr1, $attr2, $attr3);
    $product->addItemToCart();

    header('Location: ../cart');
    exit;
  }

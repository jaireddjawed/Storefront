<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/CsrfToken.php');

  class Sku {
    private $productId;
    private $price;
    private $inventory;
    private $quantity;

    private $attr1;
    private $attr2;
    private $attr3;

    private $connection;

    public function __construct($productId, $price, $inventory, $quantity)
    {
      $this->productId = $productId;
      $this->price = $price;
      $this->inventory = $inventory;
      $this->quantity = $quantity;

      $this->connection = createDBConnection();
    }

    public function __destruct()
    {
      $this->connection->close();
    }

    public function setAttributeValues($attr1, $attr2, $attr3)
    {
      $this->attr1 = $attr1;
      $this->attr2 = $attr2;
      $this->attr3 = $attr3;
    }

    public function saveSkuInDatabase()
    {
      $skuId = uniqid();
      $skuStmt = '
        INSERT INTO `skus` (product_id, sku_id, attribute_1, attribute_2, attribute_3, price, quantity)
        VALUES (?, ?, ?, ?, ?, ?, ?)';
      $skuStmt = $this->connection->prepare($skuStmt);
      $skuStmt->bind_param('sssssii', $this->productId, $skuId, $this->attr1, $this->attr2, $this->attr3, $this->price, $this->quantity);

      if (!$skuStmt->execute())
      {
        echo('Error saving sku.');
        exit;
      }
    }

    public static function listAttributes($connection, $productId)
    {
      $productStmt = 'SELECT `attributes` FROM `products` WHERE `product_id` = ?';
      $productStmt = $connection->prepare($productStmt);
      $productStmt->bind_param('s', $productId);

      $productStmt->execute();
      $productStmt->store_result();

      $productStmt->bind_result($attributes);
      $productStmt->fetch();

      return explode(', ', $attributes);
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
      'max_range' => 1000000
    ];

    // Get SKU Values
    $productId = filter_input(INPUT_POST, 'product-id', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, $options);
    $inventory = filter_input(INPUT_POST, 'inventory', FILTER_SANITIZE_STRING);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT, $options);

    // Get Attribute Values
    $attr1 = filter_input(INPUT_POST, 'attribute-0', FILTER_SANITIZE_STRING);
    $attr2 = filter_input(INPUT_POST, 'attribute-1', FILTER_SANITIZE_STRING);
    $attr3 = filter_input(INPUT_POST, 'attribute-2', FILTER_SANITIZE_STRING);

    $createSku = new Sku($productId, $price, $inventory, $quantity);
    $createSku->setAttributeValues($attr1, $attr2, $attr3);
    $createSku->saveSkuInDatabase();

    header('Location: ../product-info/?productId=' . $productId);
    exit;
  }

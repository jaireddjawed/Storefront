<?php
  require_once('../../includes/DBConnection.php');

  class ProductPrice {
    private $attr1;
    private $attr2;
    private $attr3;
    private $connection;

    public function __construct($attr1, $attr2, $attr3) {
      $this->attr1 = $attr1;
      $this->attr2 = $attr2;
      $this->attr3 = $attr3;

      $this->connection = createDBConnection();
    }

    public function __destruct() {
      $this->connection->close();
    }

    public function checkIfAttrIsDefined($attr) {
      return $attr != null ? '= "'.$attr.'"' : 'IS NULL';
    }

    public function getPrice() {
      $attr1 = $this->checkIfAttrIsDefined($this->attr1);
      $attr2 = $this->checkIfAttrIsDefined($this->attr2);
      $attr3 = $this->checkIfAttrIsDefined($this->attr3);

      // Get Product Price Based on Attributes
      $skuStmt = 'SELECT `price` FROM `skus` WHERE `attribute_1` ' . $attr1 . ' AND  `attribute_2`' . $attr2 . ' AND `attribute_3` ' . $attr3 . ' LIMIT 1';
      $skuStmt = $this->connection->query($skuStmt);
      $sku = $skuStmt->fetch_row();

      return $sku[0];
    }
  }


  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    // get current attribute values
    $attr1 = isset($_COOKIE['attribute-0']) ? $_COOKIE['attribute-0'] : null;
    $attr2 = isset($_COOKIE['attribute-1']) ? $_COOKIE['attribute-1'] : null;
    $attr3 = isset($_COOKIE['attribute-2']) ? $_COOKIE['attribute-2'] : null;

    $productPrice = new ProductPrice($attr1, $attr2, $attr3);
    $price = $productPrice->getPrice();

    echo($price);
  }

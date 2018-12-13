<?php

  function listProducts($connection) {
    $productsStmt = 'SELECT `product_id`, `title`, `images` FROM `products`';
    return $connection->query($productsStmt);
  }

<?php

  function listProducts($connection) {
    $productsStmt = 'SELECT `product_id`, `title`, `created_at` FROM `products`';
    return $connection->query($productsStmt);
  }

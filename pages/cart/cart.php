<?php

  function getCartItems($connection, $userId) {
    $cartStmt = 'SELECT `product_id`, `sku_id`, `quantity` FROM `cart_items` WHERE `user_id` = "'.$userId.'"';
    return $connection->query($cartStmt);
  }

  function getProduct ($connection, $productId) {
    $productStmt = 'SELECT `title`, `images` FROM `products` WHERE `product_id` = "'.$productId.'"';
    $productStmt = $connection->query($productStmt);
    $product = $productStmt->fetch_row();

    return [
      'title' => $product[0],
      'images' => explode(', ', $product[1]),
    ];
  }

  function getSkuPrice($connection, $productId, $skuId) {
    $skuStmt = 'SELECT `price` FROM `skus` WHERE `product_id` = "'.$productId.'" AND `sku_id` = "'.$skuId.'"';
    $skuStmt = $connection->query($skuStmt);
    $sku = $skuStmt->fetch_row();
    $price = $sku[0];

    return $price;
  }

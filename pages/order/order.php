<?php

  function getOrder($connection, $orderId) {
    $orderStmt = 'SELECT `street_address`, `street_address_2`, `city`, `state`, `postal_code` FROM `orders` WHERE `order_id` = "'.$orderId.'"';
    $orderStmt = $connection->query($orderStmt);
    $order = $orderStmt->fetch_row();

    return [
      'street_address' => $order[0],
      'street_address_2' => $order[1],
      'city' => $order[2],
      'state' => $order[3],
      'postal_code' => $order[4]
    ];
  }

  function listOrderItems($connection, $orderId) {
    $orderItemStmt = 'SELECT `product_id`, `sku_id` FROM `order_items` WHERE `order_id` = "'.$orderId.'"';
    return $connection->query($orderItemStmt);
  }

  function getProduct($connection, $productId) {
    $productStmt = 'SELECT `title`, `images` FROM `products` WHERE `product_id` = "'.$productId.'"';
    $productStmt = $connection->query($productStmt);
    $product = $productStmt->fetch_row();

    return [
      'title' => $product[0],
      'images' => explode(', ', $product[1])
    ];
  }

  function getSku($connection, $productId, $skuId) {
    $skuStmt = 'SELECT `price`, `attribute_1`, `attribute_2`, `attribute_3` FROM `skus` WHERE `product_id` = "'.$productId.'" AND `sku_id` = "'.$skuId.'" LIMIT 1';
    $skuStmt = $connection->query($skuStmt);
    $sku = $skuStmt->fetch_row();

    return [
      'price' => $sku[0],
      'attribute_1' => $sku[1],
      'attribute_2' => $sku[2],
      'attribute_3' => $sku[3],
    ];
  }

<?php
  function listOrders($connection)
  {
    $orderStmt = 'SELECT `order_id`, `street_address`, `street_address_2`, `city`, `state`, `postal_code` FROM `orders`';
    return $connection->query($orderStmt);
  }


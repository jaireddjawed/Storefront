<?php
  require_once('../includes/DBConnection.php');

  $connection = createDBConnection();
  $sql = '
    CREATE TABLE IF NOT EXISTS `order_items` (
      `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `order_id` VARCHAR(30) NOT NULL,
      `product_id` VARCHAR(30) NOT NULL,
      `sku_id` VARCHAR(30) NOT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
  ';

  if ($connection->query($sql) == true)
  {
    $connection->close();
    echo('Orders table created successfully!');
  }
  else {
    echo('Error creating table: ' . $connection->error);
  }

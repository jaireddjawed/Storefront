<?php
  require_once('../includes/DBConnection.php');

  $connection = createDBConnection();
  $sql = '
    CREATE TABLE IF NOT EXISTS `cart_items` (
      `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `product_id` VARCHAR(30) NOT NULL,
      `sku_id` VARCHAR(30) NOT NULL,
      `quantity` INT(6) NOT NULL,
      `user_id` INT(6) NOT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
  ';

  if ($connection->query($sql) == true)
  {
    $connection->close();
    echo('Cart table created successfully!');
  }
  else {
    echo('Error creating table: ' . $connection->error);
  }

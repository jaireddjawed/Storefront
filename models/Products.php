<?php
  require_once('../includes/DBConnection.php');

  $connection = createDBConnection();
  $sql = '
    CREATE TABLE IF NOT EXISTS `products` (
      `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `product_id` VARCHAR(30) NOT NULL,
      `title`  VARCHAR(100) NOT NULL,
      `caption` VARCHAR(100),
      `description` VARCHAR(1000) NOT NULL,
      `attributes` VARCHAR(100) NOT NULL,
      `images` VARCHAR(1000) NOT NULL,
      `shippable` BOOLEAN DEFAULT TRUE,
      `width` INT(6) DEFAULT 0,
      `height` INT(6) DEFAULT 0,
      `length` INT(6) DEFAULT 0,
      `weight` INT(6) DEFAULT 0,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
  ';

  if ($connection->query($sql) == true)
  {
    $connection->close();
    echo('Products table created successfully!');
  }
  else {
    echo('Error creating table: ' . $connection->error);
  }

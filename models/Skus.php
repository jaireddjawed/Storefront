<?php
  require_once('../includes/DBConnection.php');

  $connection = createDBConnection();
  $sql = '
    CREATE TABLE IF NOT EXISTS `skus` (
      `id` INT(6) AUTO_INCREMENT PRIMARY KEY,
      `product_id` VARCHAR(30) NOT NULL,
      `sku_id` VARCHAR(30) NOT NULL,
      `attribute_1` VARCHAR(100),
      `attribute_2` VARCHAR(100),
      `attribute_3` VARCHAR(100),
      `price` INT(6) NOT NULL,
      `quantity` INT(6) NOT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
  ';

  if ($connection->query($sql) == true)
  {
    $connection->close();
    echo('Skus table created successfully!');
  }
  else {
    echo('Error creating table: ' . $connection->error);
  }

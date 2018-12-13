<?php
  require_once('../includes/DBConnection.php');

  $connection = createDBConnection();
  $sql = '
    CREATE TABLE IF NOT EXISTS `orders` (
      `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `order_id` VARCHAR(30) NOT NULL,
      `user_id` VARCHAR(30) NOT NULL,
      `street_address` VARCHAR(100) NOT NULL,
      `street_address_2` VARCHAR(100),
      `city` VARCHAR(100) NOT NULL,
      `state` VARCHAR(100) NOT NULL,
      `postal_code` VARCHAR(10) NOT NULL,
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

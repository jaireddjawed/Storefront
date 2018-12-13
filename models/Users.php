<?php
  require_once('../includes/DBConnection.php');

  $connection = createDBConnection();
  $sql = "
    CREATE TABLE IF NOT EXISTS `users` (
      `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `first_name` VARCHAR(30) NOT NULL,
      `last_name` VARCHAR(30) NOT NULL,
      `email_address` VARCHAR(50) NOT NULL,
      `password` CHAR(128) NOT NULL,
      `salt` CHAR(128) NOT NULL,
      `role` ENUM('admin', 'basic') DEFAULT 'basic' NOT NULL,
      `customer_id` VARCHAR(30) NOT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
  ";

  if ($connection->query($sql) == true)
  {
    $connection->close();
    echo('Users table created successfully!');
  }
  else {
    echo('Error creating table: ' . $connection->error);
  }

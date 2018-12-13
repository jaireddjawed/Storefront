<?php
  require_once('../includes/DBConnection.php');

  $connection = createDBConnection();
  $sql = '
    CREATE TABLE IF NOT EXISTS `login_attempts` (
      `user_id` INT(11) NOT NULL,
      `time` VARCHAR(30) NOT NULL
    );
  ';

  if ($connection->query($sql) == true)
  {
    $connection->close();
    echo('Login Attempts table created successfully!');
  }
  else {
    echo('Error creating table: ' . $connection->error);
  }

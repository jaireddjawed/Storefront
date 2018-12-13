<?php
  require_once('../includes/DBConnection.php');

  $connection = createDBConnection();

  $firstName = 'John';
  $lastName = 'Doe';
  $email = 'johndoeadmin@localhost.com';
  $userRole = 'admin';
  $customerId = 'cus_E92ohquCKr4jn1';
  $userPassword = '3ac8b334c7139311b4a8f149f6588354e58715acaaf5cc37c5ff6f788b586e43cd113aca54ac1eb7aa3f8c3de16bc0b2a888ccb204c9d23e0841e59f678cae63';
  $userSalt = 'caec9bb0ae90d4a49fd0a893171658eadccfe6384da0aa267928b353d806c1fcd34309b7ec1666aaf09d9da46ddeeee2a070a6506bb2d6c00692cb9c61b043f8';

  $sql = '
    INSERT INTO `users` (first_name, last_name, email_address, password, salt, role, customer_id)
    VALUES ("'.$firstName.'", "'.$lastName.'", "'.$email.'", "'.$userPassword.'", "'.$userSalt.'", "'.$userRole.'", "'.$customerId.'");
  ';

  $connection->query($sql);

  $firstName = 'John';
  $lastName = 'Doe';
  $email = 'johndoebasic@localhost.com';
  $userRole = 'basic';
  $customerId = 'cus_E92ohquCKr4jn1';
  $userPassword = '3ac8b334c7139311b4a8f149f6588354e58715acaaf5cc37c5ff6f788b586e43cd113aca54ac1eb7aa3f8c3de16bc0b2a888ccb204c9d23e0841e59f678cae63';
  $userSalt = 'caec9bb0ae90d4a49fd0a893171658eadccfe6384da0aa267928b353d806c1fcd34309b7ec1666aaf09d9da46ddeeee2a070a6506bb2d6c00692cb9c61b043f8';

  $sql = '
    INSERT INTO `users` (first_name, last_name, email_address, password, salt, role, customer_id)
    VALUES ("'.$firstName.'", "'.$lastName.'", "'.$email.'", "'.$userPassword.'", "'.$userSalt.'", "'.$userRole.'", "'.$customerId.'");
  ';

  $connection->query($sql);

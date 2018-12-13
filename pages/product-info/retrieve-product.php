<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/CsrfToken.php');
  require_once('../../includes/LoginFunctions.php');

  class Product
  {
    public static function getProductById($connection, $productId)
    {
      $productStmt = '
        SELECT `title`, `caption`, `description`, `attributes`, `images`
        FROM `products` WHERE `product_id` = ? LIMIT 1';
      $productStmt = $connection->prepare($productStmt);
      $productStmt->bind_param('s', $productId);
      $productStmt->execute();
      $productStmt->store_result();

      $productStmt->bind_result($title, $caption, $desc, $attributes, $images);
      $productStmt->fetch();

      if ($productStmt->num_rows == 1) {
        $productStmt->close();
        return [
          'title' => $title,
          'caption' => $caption,
          'description' => $desc,
          'attributes' => explode(', ', $attributes),
          'images' => explode(', ', $images),
        ];
      }

      return null;
    }

    public static function removeProduct($connection, $productId)
    {
      $role = checkUserRole($connection);

      if ($role == 'basic') {
        echo('You\'re not authorized to delete this product.');
      }

      $productStmt = 'DELETE FROM `products` WHERE `product_id` = ?';
      $productStmt = $connection->prepare($productStmt);
      $productStmt->bind_param('s', $productId);

      if (!$productStmt->execute())
      {
        echo('Failed to remove product');
        exit;
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    $connection = createDBConnection();
    $productId = filter_input(INPUT_POST, 'product-id', FILTER_SANITIZE_STRING);

    $csrfFormToken = filter_input(INPUT_POST, 'csrf-token', FILTER_SANITIZE_STRING);
    $sessionCsrfToken = $_SESSION['csrf_token'];

    // Verify the CSRF Token is correct
    if ($csrfFormToken != $sessionCsrfToken)
    {
      echo('Invalid Csrf Token!');
      exit;
    }

    Product::removeProduct($connection, $productId);
    $connection->close();

    header('Location: ../list-products');
    exit;
  }

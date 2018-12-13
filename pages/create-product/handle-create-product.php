<?php
  require_once('../../includes/DBConnection.php');
  require_once('../../includes/CsrfToken.php');
  require_once('../../includes/LoginFunctions.php');

  class HandleCreateProduct
  {
    private $productId;
    private $title;
    private $caption;
    private $desc;
    private $attributes;
    private $shippable;
    private $images;
    private $stats;

    private $connection;

    public function __construct($productId, $title, $caption, $desc, $attributes, $shippable)
    {
      $this->productId = $productId;
      $this->title = $title;
      $this->caption = $caption;
      $this->desc = $desc;
      $this->attributes = $attributes;
      $this->shippable = $shippable == 'shippable';

      $this->connection = createDBConnection();
    }

    public function __destruct()
    {
      $this->connection->close();
    }

    public function checkUserPriveleges()
    {
      // The user must be an admin to create a product
      $role = checkUserRole($this->connection);

      if ($role === 'basic')
      {
        echo('User does not have priveleges!');
        exit;
      }
    }

    public function setProductStats($width, $height, $length, $weight)
    {
      $this->stats = [
        'width' => $width,
        'height' => $height,
        'length' => $length,
        'weight' => $weight
      ];
    }

    public function uploadProductImages($images)
    {
      $i = 0;
      $imageNames = '';
      $imageCount = count($images['name']);

      if ($imageCount > 10) {
        echo('A maximum of 10 images is allowed!');
        exit;
      }

      while ($i < count($images['name']))
      {
        $targetDir = '../../uploads/';
        $tempFilePath = $images['tmp_name'][$i];
        $newFilePath = $targetDir . basename($images['name'][$i]);
        $imageFileType = strtolower(pathinfo($newFilePath, PATHINFO_EXTENSION));

        if (file_exists($newFilePath)) {
          echo('Image already exists');
          exit;
        }
        else if ($imageFileType != 'jpg' && $imageFileType != 'png' &&
          $imageFileType != 'jpeg' && $imageFileType != 'gif') {
            echo('Sorry, only jpg, jpeg, png, and gif files are allowed.');
            exit;
        }

        // Upload the image
        move_uploaded_file($tempFilePath, $newFilePath);

        // save file names to image string
        $imageNames .= $newFilePath . ', ';
        $i++;
      }

      $this->images = $imageNames;
    }

    public function saveProductInDatabase()
    {
      // Create a product Id so the product can be seen later
      $productStmt = '
        INSERT INTO `products` (product_id, title, caption, description, attributes, shippable, images, width, height, length, weight)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
      ';
      $productStmt = $this->connection->prepare($productStmt);
      $productStmt->bind_param('sssssisiiii',
        $this->productId, $this->title, $this->caption, $this->desc, $this->attributes,
        $this->shippable, $this->images, $this->stats['width'], $this->stats['height'], $this->stats['length'], $this->stats['weight']);

      if (!$productStmt->execute())
      {
        echo('Error saving product into database!' . $productStmt->error);
        exit;
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    $csrfFormToken = filter_input(INPUT_POST, 'csrf-token', FILTER_SANITIZE_STRING);
    $sessionCsrfToken = $_SESSION['csrf_token'];

    // Verify the CSRF Token is correct
    if ($csrfFormToken != $sessionCsrfToken)
    {
      echo('Invalid Csrf Token!');
      exit;
    }

    // Product Info
    $productId = uniqid();
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $caption = filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_STRING);
    $desc = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $attributes = filter_input(INPUT_POST, 'attributes', FILTER_SANITIZE_STRING);
    $shippable = filter_input(INPUT_POST, 'shippable', FILTER_SANITIZE_STRING);

    $images = $_FILES['images'];

    // Rules for recieving integer input
    $options = [
      'default' => 0,
      'min_range' => 0,
      'max_range' => 200
    ];

    // Product Stats
    $width = filter_input(INPUT_POST, 'width', FILTER_SANITIZE_NUMBER_INT, $options);
    $height = filter_input(INPUT_POST, 'height', FILTER_SANITIZE_NUMBER_INT, $options);
    $length = filter_input(INPUT_POST, 'length', FILTER_SANITIZE_NUMBER_INT, $options);
    $weight = filter_input(INPUT_POST, 'weight', FILTER_SANITIZE_NUMBER_INT, $options);

    $createProduct = new HandleCreateProduct($productId, $title, $caption, $desc, $attributes, $shippable);
    $createProduct->checkUserPriveleges();
    $createProduct->setProductStats($width, $height, $length, $weight);
    $createProduct->uploadProductImages($images);
    $createProduct->saveProductInDatabase();

    // redirect to view the created product
    header('Location: ../product-info/?productId=' . $productId);
    exit;
  }

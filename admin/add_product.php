<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: admin_login.php');
  exit;
}

if (isset($_POST['add'])) {
  $name = $_POST['name'];
  $description = $_POST['description'];
  $category = $_POST['category'];
  $size = $_POST['size'];
  $price = $_POST['price'];
  $image = $_FILES['image']['name'];
  $tmp = $_FILES['image']['tmp_name'];

  move_uploaded_file($tmp, "../image/$image");

  mysqli_query($conn, "INSERT INTO products (name, description, category, price, size, image)
    VALUES ('$name', '$description', '$category', '$price', '$size', '$image')");

  header('Location: admin_dashboard.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product | Modeva Admin</title>
  <link rel="stylesheet" href="../css/user_style.css">
</head>
<body class="auth-body">
  <div class="auth-container">
    <div class="auth-box">
      <h2>Add New Product</h2>
      <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <textarea name="description" placeholder="Product Description" required></textarea>
        <select name="category" required>
          <option value="">Select Category</option>
          <option value="Women">Women</option>
          <option value="Men">Men</option>
          <option value="Kids">Kids</option>
        </select>
        <select name="size" required>
          <option value="">Select Size</option>
          <option value="Small">Small</option>
          <option value="Medium">Medium</option>
          <option value="Large">Large</option>
          <option value="XL">XL</option>
        </select>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="add" class="btn-auth">Add Product</button>
      </form>
    </div>
  </div>
</body>
</html>

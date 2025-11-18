<?php
include '../components/connect.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: admin_login.php');
  exit;
}

$success = '';
$error = '';

if (isset($_POST['add'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $category = mysqli_real_escape_string($conn, $_POST['category']);
  $size = mysqli_real_escape_string($conn, $_POST['size']);
  $price = mysqli_real_escape_string($conn, $_POST['price']);
  
  $image = $_FILES['image']['name'];
  $tmp = $_FILES['image']['tmp_name'];
  $path = "../image/" . basename($image);

  if (!empty($name) && !empty($description) && !empty($category) && !empty($size) && !empty($price) && !empty($image)) {
    if (move_uploaded_file($tmp, $path)) {
      $insert = mysqli_query($conn, "INSERT INTO products (name, description, category, price, size, image)
        VALUES ('$name', '$description', '$category', '$price', '$size', '$image')");
      
      if ($insert) {
        $success = "✅ Product added successfully!";
      } else {
        $error = "❌ Database error. Please try again.";
      }
    } else {
      $error = "❌ Failed to upload image.";
    }
  } else {
    $error = "⚠️ Please fill all required fields.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Modeva Couture</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/user_style.css">
  <style>
    body { background:#fffdf8; font-family:'Poppins',sans-serif; margin:0; }
    .dashboard {
      max-width:700px; margin:auto; background:#fff; padding:40px 50px;
      border-radius:15px; box-shadow:0 5px 25px rgba(0,0,0,0.1);
    }
    h2 { text-align:center; color:var(--dark-brown); margin-bottom:20px; }
    form { display:flex; flex-direction:column; gap:15px; }
    input, select, textarea { padding:12px; border:1px solid #ccc; border-radius:10px; font-size:15px; }
    textarea { resize:none; height:100px; }
    button { background:var(--main-color); color:#fff; border:none; padding:12px; border-radius:30px;
             font-weight:600; cursor:pointer; transition:0.3s; }
    button:hover { background:var(--dark-brown); }
    .message { text-align:center; font-weight:500; margin-bottom:15px; }
    .success { color:green; }
    .error { color:red; }
    .toggle-btn {
      display:none; position:fixed; top:15px; left:15px; background:#f0b61f; color:#fff;
      border:none; padding:10px 14px; border-radius:6px; cursor:pointer; z-index:1001;
    }

    @media(max-width:768px){
      .toggle-btn{display:block;}
      .admin-sidebar{left:-240px; transition:0.3s;}
      .admin-sidebar.active{left:0;}
      .admin-content{margin-left:0;padding-top:60px;}
    }
  </style>
</head>
<body>

<!-- Sidebar toggle for mobile -->
<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

<?php include 'admin_sidebar.php'; ?>

<div class="admin-content">
  <div class="dashboard">
    <h2>Add Product to Menu</h2>

    <?php if ($success): ?>
      <p class="message success"><?= $success; ?></p>
    <?php elseif ($error): ?>
      <p class="message error"><?= $error; ?></p>
    <?php endif; ?>

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
        <option value="S">S</option>
        <option value="M">M</option>
        <option value="L">L</option>
        <option value="XL">XL</option>
      </select>

      <input type="number" step="0.01" name="price" placeholder="Price" required>
      <input type="file" name="image" accept="image/*" required>

      <button type="submit" name="add">Add Product</button>
    </form>
  </div>
</div>

<script>
function toggleSidebar() {
  document.querySelector('.admin-sidebar').classList.toggle('active');
}
</script>

</body>
</html>

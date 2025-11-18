<?php
include '../components/connect.php';
session_start();

// ✅ Handle new product submission
if (isset($_POST['add_product'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $category = mysqli_real_escape_string($conn, $_POST['category']);
  $size = mysqli_real_escape_string($conn, $_POST['size']);
  $price = floatval($_POST['price']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);

  // Handle image upload
  $imageName = '';
  if (!empty($_FILES['image']['name'])) {
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $imagePath = "../image/" . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
  }

  // Insert new product
  $query = "
    INSERT INTO products (name, category, size, price, description, image, stock)
    VALUES ('$name', '$category', '$size', '$price', '$description', '$imageName', '{$_POST['stock']}')
  ";
  mysqli_query($conn, $query);

  echo "<script>alert('✅ Product added successfully!'); window.location='admin_products.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Product | Modeva Couture Admin</title>
  <link rel="stylesheet" href="../css/user_style.css">
  <style>
    body {
      font-family:'Poppins',sans-serif;
      background:#fafafa;
      margin:0;
    }

    main {
      max-width:700px;
      margin:130px auto;
      padding:40px;
      background:#fff;
      border-radius:12px;
      box-shadow:0 4px 20px rgba(0,0,0,.08);
    }

    h1 {
      text-align:center;
      font-size:1.8rem;
      margin-bottom:25px;
    }

    label {
      display:block;
      font-weight:500;
      margin-top:15px;
      margin-bottom:5px;
      color:#333;
    }

    input, textarea, select {
      width:100%;
      padding:10px;
      border:1px solid #ddd;
      border-radius:8px;
      font-family:inherit;
      font-size:1rem;
    }

    textarea { resize:none; height:100px; }

    .btn {
      margin-top:25px;
      background:#f0b61f;
      color:#fff;
      border:none;
      padding:12px 25px;
      border-radius:6px;
      cursor:pointer;
      font-size:1rem;
      transition:0.3s;
      width:100%;
    }

    .btn:hover { background:#d39c10; }

    .preview {
      width:100px;
      height:100px;
      object-fit:cover;
      border-radius:8px;
      margin-top:10px;
      display:none;
    }

    .back-link {
      display:inline-block;
      margin-top:20px;
      color:#f0b61f;
      text-decoration:none;
      font-weight:500;
    }

    .back-link:hover {
      color:#d39c10;
      text-decoration:underline;
    }

    /* Sidebar toggle button */
    .toggle-btn {
      display:none;
      position:fixed;
      top:15px;
      left:15px;
      background:#f0b61f;
      color:#fff;
      border:none;
      padding:10px 14px;
      border-radius:6px;
      cursor:pointer;
      z-index:1001;
    }

    @media(max-width:768px){
      .toggle-btn{display:block;}
      .admin-sidebar{left:-240px;transition:0.3s;}
      .admin-sidebar.active{left:0;}
      .admin-content{margin-left:0;padding-top:60px;}
    }
  </style>
</head>
<body>

<!-- Sidebar toggle for small screens -->
<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

<?php include 'admin_sidebar.php'; ?>

<div class="admin-content">
  <main>
    <h1>Add New Product</h1>

    <form action="" method="POST" enctype="multipart/form-data">
      <label>Product Name</label>
      <input type="text" name="name" placeholder="e.g. Elegant Long Dress" required>

      <label>Category</label>
      <select name="category" required>
        <option value="">-- Select Category --</option>
        <option value="Women">Women</option>
        <option value="Men">Men</option>
        <option value="Kids">Kids</option>
      </select>

      <label>Available Size</label>
      <select name="size" required>
        <option value="">-- Select Size --</option>
        <option value="S">S</option>
        <option value="M">M</option>
        <option value="L">L</option>
        <option value="XL">XL</option>
      </select>

      <label>Stock Quantity</label>
      <input type="number" name="stock" min="0" placeholder="e.g. 25" required>

      <label>Price ($)</label>
      <input type="number" name="price" step="0.01" placeholder="e.g. 45.99" required>

      <label>Description</label>
      <textarea name="description" placeholder="Write a short product description..." required></textarea>

      <label>Product Image</label>
      <input type="file" name="image" accept="image/*" required onchange="previewImage(event)">
      <img id="imagePreview" class="preview">

      <button type="submit" name="add_product" class="btn">➕ Add Product</button>
    </form>

    <a href="admin_products.php" class="back-link">← Back to Products</a>
  </main>
</div>

<script>
function previewImage(event) {
  const image = document.getElementById('imagePreview');
  image.src = URL.createObjectURL(event.target.files[0]);
  image.style.display = 'block';
}

function toggleSidebar() {
  document.querySelector('.admin-sidebar').classList.toggle('active');
}
</script>

</body>
</html>

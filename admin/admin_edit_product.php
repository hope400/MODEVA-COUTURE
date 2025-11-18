<?php
include '../components/connect.php';
session_start();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ✅ Fetch product info
$product = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
if (!$product || mysqli_num_rows($product) == 0) {
  die("<h2 style='text-align:center;margin-top:100px;'>Product not found.</h2>");
}
$data = mysqli_fetch_assoc($product);

// ✅ Handle updates
if (isset($_POST['update'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $price = floatval($_POST['price']);
  $desc = mysqli_real_escape_string($conn, $_POST['description']);
  $category = mysqli_real_escape_string($conn, $_POST['category']);
  $size = mysqli_real_escape_string($conn, $_POST['size']);
  $image = $data['image']; // keep current image by default

  if (!empty($_FILES['image']['name'])) {
    $image = time() . '_' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../image/" . $image);
  }

  $update = "
    UPDATE products 
    SET name='$name', price='$price', description='$desc', category='$category', size='$size', image='$image', stock='{$_POST['stock']}'
    WHERE id=$id
  ";
  mysqli_query($conn, $update);
  echo "<script>alert('✅ Product updated successfully!'); window.location='admin_products.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Product | Modeva Couture Admin</title>
  <link rel="stylesheet" href="../css/user_style.css">
  <style>
    body{font-family:'Poppins',sans-serif;background:#fafafa;margin:0;}

    main{
      max-width:700px;margin:130px auto;padding:40px;background:#fff;
      border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.08);
    }

    h1{text-align:center;margin-bottom:25px;}
    label{display:block;font-weight:500;margin-top:15px;margin-bottom:5px;}
    input,textarea,select{
      width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;
      font-family:inherit;font-size:1rem;
    }
    textarea{resize:none;height:100px;}
    .btn{
      margin-top:20px;background:#f0b61f;color:#fff;border:none;
      padding:12px 25px;border-radius:6px;cursor:pointer;font-size:1rem;
      transition:0.3s;
    }
    .btn:hover{background:#d39c10;}
    img.preview{width:100px;height:100px;border-radius:8px;object-fit:cover;margin-top:10px;}

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
    <h1>Edit Product</h1>

    <form action="" method="POST" enctype="multipart/form-data">
      <label>Product Name</label>
      <input type="text" name="name" value="<?= htmlspecialchars($data['name']); ?>" required>

      <label>Category</label>
      <input type="text" name="category" value="<?= htmlspecialchars($data['category']); ?>" required>

      <label>Size</label>
      <input type="text" name="size" value="<?= htmlspecialchars($data['size']); ?>">

      <label>Price ($)</label>
      <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($data['price']); ?>" required>

      <label>Stock Quantity</label>
      <input type="number" name="stock" min="0" value="<?= htmlspecialchars($data['stock']); ?>" required>

      <label>Description</label>
      <textarea name="description" required><?= htmlspecialchars($data['description']); ?></textarea>

      <label>Current Image</label><br>
      <img src="../image/<?= $data['image']; ?>" class="preview" alt="Product Image">

      <label>Upload New Image (optional)</label>
      <input type="file" name="image" accept="image/*">

      <button type="submit" name="update" class="btn">💾 Save Changes</button>
    </form>
  </main>
</div>

<script>
function toggleSidebar() {
  document.querySelector('.admin-sidebar').classList.toggle('active');
}
</script>

</body>
</html>

<?php
include '../components/connect.php';
session_start();

// ✅ Handle product deletion
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  mysqli_query($conn, "DELETE FROM products WHERE id = $id");
  echo "<script>alert('🗑 Product deleted successfully!'); window.location='admin_products.php';</script>";
  exit;
}

// ✅ Fetch all products
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Manage Products | Modeva Couture</title>
  <link rel="stylesheet" href="../css/user_style.css">
  <style>
    body {
      font-family:'Poppins',sans-serif;
      background:#fafafa;
      margin:0;
    }

    main {
      max-width:1100px;
      margin:130px auto;
      padding:40px;
      background:#fff;
      border-radius:12px;
      box-shadow:0 4px 20px rgba(0,0,0,.08);
    }

    h1 {
      text-align:center;
      margin-bottom:30px;
    }

    table {
      width:100%;
      border-collapse:collapse;
    }

    th, td {
      border-bottom:1px solid #ddd;
      padding:12px;
      text-align:center;
    }

    th {
      background:#f0b61f;
      color:#fff;
    }

    tr:hover {
      background:#fff7e6;
    }

    img {
      width:70px;
      height:70px;
      object-fit:cover;
      border-radius:8px;
    }

    .btn {
      background:#f0b61f;
      color:#fff;
      padding:8px 18px;
      border:none;
      border-radius:6px;
      text-decoration:none;
      cursor:pointer;
      font-size:14px;
      transition:0.3s;
    }

    .btn:hover {
      background:#d39c10;
    }

    .delete-btn {
      background:crimson;
    }

    .delete-btn:hover {
      background:darkred;
    }

    .add-btn {
      display:inline-block;
      margin-bottom:20px;
      background:#27ae60;
      padding:10px 20px;
      color:#fff;
      border-radius:8px;
      text-decoration:none;
      font-weight:500;
    }

    .add-btn:hover {
      background:#1e874b;
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
      table{font-size:0.85rem;}
      img{width:60px;height:60px;}
    }
  </style>
</head>
<body>

<!-- Sidebar toggle for mobile -->
<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

<?php include 'admin_sidebar.php'; ?>

<div class="admin-content">
  <main>
    <h1>Manage Products</h1>

    <a href="admin_add_product.php" class="add-btn">➕ Add New Product</a>

    <table>
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Category</th>
          <th>Size</th>
          <th>Price ($)</th>
          <th>Stock</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><img src="../image/<?= $row['image']; ?>" alt="<?= $row['name']; ?>"></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['category']); ?></td>
            <td><?= htmlspecialchars($row['size']); ?></td>
            <td><?= number_format($row['price'], 2); ?></td>

            <td style="font-weight:600;
                color:<?= ($row['stock'] == 0 ? 'crimson' : ($row['stock'] < 5 ? '#e67e22' : '#27ae60')); ?>;">
              <?= $row['stock'] == 0 ? 'Out of Stock' : $row['stock']; ?>
            </td>

            <td>
              <a href="admin_edit_product.php?id=<?= $row['id']; ?>" class="btn">✏️ Edit</a>
              <a href="?delete=<?= $row['id']; ?>" class="btn delete-btn" 
                 onclick="return confirm('Are you sure you want to delete this product?');">🗑 Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>

<script>
function toggleSidebar() {
  document.querySelector('.admin-sidebar').classList.toggle('active');
}
</script>

</body>
</html>

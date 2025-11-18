<?php
include '../components/connect.php';
session_start();

// ✅ Secure access
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: admin_login.php');
  exit;
}

// ✅ Handle new discount submission
if (isset($_POST['add_discount'])) {
  $product_id = intval($_POST['product_id']);
  $discount_type = mysqli_real_escape_string($conn, $_POST['discount_type']);
  $discount_value = floatval($_POST['discount_value']);
  $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
  $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);

  // Insert discount
  $query = "
    INSERT INTO discounts (product_id, discount_type, discount_value, start_date, end_date)
    VALUES ('$product_id', '$discount_type', '$discount_value', '$start_date', '$end_date')
  ";
  mysqli_query($conn, $query);

  echo "<script>alert('✅ Discount added successfully!'); window.location='admin_discounts.php';</script>";
  exit;
}

// ✅ Handle deletion
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  mysqli_query($conn, "DELETE FROM discounts WHERE id = $id");
  echo "<script>alert('🗑 Discount deleted successfully!'); window.location='admin_discounts.php';</script>";
  exit;
}

// ✅ Fetch all discounts with product names
$result = mysqli_query($conn, "
  SELECT d.*, p.name AS product_name
  FROM discounts d
  JOIN products p ON d.product_id = p.id
  ORDER BY d.id DESC
");

// ✅ Fetch product list for dropdown
$products = mysqli_query($conn, "SELECT id, name FROM products ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Discounts | Modeva Admin</title>
  <link rel="stylesheet" href="../css/user_style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fafafa;
      margin: 0;
    }

    main {
      max-width: 1100px;
      margin: 130px auto;
      padding: 40px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,.08);
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
    }

    form.add-discount {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 30px;
    }

    label {
      display: block;
      font-weight: 500;
      margin-bottom: 5px;
      color: #333;
    }

    input, select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    .btn {
      grid-column: 1 / 3;
      background: #f0b61f;
      color: #fff;
      border: none;
      padding: 12px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: 0.3s;
    }

    .btn:hover {
      background: #d39c10;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      border-bottom: 1px solid #ddd;
      padding: 12px;
      text-align: center;
    }

    th {
      background: #f0b61f;
      color: #fff;
    }

    tr:hover {
      background: #fff7e6;
    }

    .delete-btn {
      background: crimson;
      color: #fff;
      padding: 6px 12px;
      border-radius: 6px;
      text-decoration: none;
      transition: 0.3s;
    }

    .delete-btn:hover {
      background: darkred;
    }

    .toggle-btn {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      background: #f0b61f;
      color: #fff;
      border: none;
      padding: 10px 14px;
      border-radius: 6px;
      cursor: pointer;
      z-index: 1001;
    }

    @media(max-width:768px){
      .toggle-btn{display:block;}
      .admin-sidebar{left:-240px;transition:0.3s;}
      .admin-sidebar.active{left:0;}
      .admin-content{margin-left:0;padding-top:60px;}
      form.add-discount{grid-template-columns:1fr;}
    }
  </style>
</head>
<body>

<!-- Sidebar toggle for mobile -->
<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

<?php include 'admin_sidebar.php'; ?>

<div class="admin-content">
  <main>
    <h1>Manage Discounts & Promotions</h1>

    <form method="POST" class="add-discount">
      <div>
        <label>Select Product</label>
        <select name="product_id" required>
          <option value="">-- Choose Product --</option>
          <?php while($p = mysqli_fetch_assoc($products)): ?>
            <option value="<?= $p['id']; ?>"><?= htmlspecialchars($p['name']); ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div>
        <label>Discount Type</label>
        <select name="discount_type" required>
          <option value="percentage">Percentage (%)</option>
          <option value="fixed">Fixed Amount ($)</option>
        </select>
      </div>

      <div>
        <label>Discount Value</label>
        <input type="number" name="discount_value" step="0.01" min="0" required placeholder="e.g. 10 for 10%">
      </div>

      <div>
        <label>Start Date</label>
        <input type="date" name="start_date" required>
      </div>

      <div>
        <label>End Date</label>
        <input type="date" name="end_date" required>
      </div>

      <button type="submit" name="add_discount" class="btn">➕ Add Discount</button>
    </form>

    <h2 style="text-align:center;margin:30px 0 15px;">Active Discounts</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Product</th>
          <th>Type</th>
          <th>Value</th>
          <th>Start</th>
          <th>End</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while($d = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= $d['id']; ?></td>
              <td><?= htmlspecialchars($d['product_name']); ?></td>
              <td><?= ucfirst($d['discount_type']); ?></td>
              <td>
                <?= $d['discount_type'] === 'percentage' 
                    ? $d['discount_value'] . '%' 
                    : '$' . number_format($d['discount_value'], 2); ?>
              </td>
              <td><?= $d['start_date']; ?></td>
              <td><?= $d['end_date']; ?></td>
              <td>
                <a href="?delete=<?= $d['id']; ?>" class="delete-btn"
                   onclick="return confirm('Are you sure you want to delete this discount?');">
                  🗑 Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7">No discounts added yet.</td></tr>
        <?php endif; ?>
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

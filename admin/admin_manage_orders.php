<?php
include '../components/connect.php';
session_start();

// Optional: secure this page (leave as-is if you already handle auth elsewhere)
if (!isset($_SESSION['admin_id'])) {
  $_SESSION['admin_id'] = 1; // Temporary for testing
}

// Handle order status update
if (isset($_POST['update_status'])) {
  $orderId = intval($_POST['order_id']);
  $newStatus = mysqli_real_escape_string($conn, $_POST['status']);
  mysqli_query($conn, "UPDATE orders SET status='$newStatus' WHERE id='$orderId'");
  echo "<script>alert('✅ Order status updated successfully!'); window.location='admin_manage_orders.php';</script>";
  exit;
}

// Pull orders + customer full name from users table
$orders = mysqli_query($conn, "
  SELECT 
    o.*, 
    CONCAT(u.first_name, ' ', u.last_name) AS customer_name 
  FROM orders o
  LEFT JOIN users u ON o.user_id = u.id
  ORDER BY o.order_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Orders | Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/user_style.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background:#f8f8f8; margin:0; }

    main {
      max-width: 1100px;
      margin: 130px auto;
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    h1 { text-align: center; margin-bottom: 25px; }

    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border-bottom: 1px solid #ddd; text-align: center; padding: 12px; }
    th { background: #f9f9f9; font-weight: 600; }

    .status { padding: 6px 10px; border-radius: 6px; font-weight: 500; color: #fff; }
    .Pending { background: #f0b61f; }
    .Shipped { background: #3498db; }
    .Delivered { background: #27ae60; }

    .update-form select {
      padding: 6px; border-radius: 6px; border: 1px solid #ccc; font-size: 14px;
    }
    .update-form button {
      background: #f0b61f; border: none; color: #fff; padding: 6px 14px;
      border-radius: 6px; cursor: pointer; transition: .3s;
    }
    .update-form button:hover { background: #d39c10; }

    .view-link {
      background: #3498db; color: #fff; padding: 6px 14px; border-radius: 6px;
      text-decoration: none; transition: .3s; display:inline-block; margin-left:8px;
    }
    .view-link:hover { background: #2980b9; }

    /* Sidebar toggle button */
    .toggle-btn {
      display:none; position:fixed; top:15px; left:15px; background:#f0b61f; color:#fff;
      border:none; padding:10px 14px; border-radius:6px; cursor:pointer; z-index:1001;
    }

    @media(max-width:768px){
      .toggle-btn{display:block;}
      .admin-sidebar{left:-240px; transition:0.3s;}
      .admin-sidebar.active{left:0;}
      .admin-content{margin-left:0; padding-top:60px;}
      table{font-size:0.9rem;}
    }

    /* Space for sidebar */
    .admin-content { margin-left: 240px; padding: 30px; }
  </style>
</head>
<body>

<!-- Sidebar toggle for mobile -->
<button class="toggle-btn" onclick="document.querySelector('.admin-sidebar').classList.toggle('active')">☰</button>

<?php include 'admin_sidebar.php'; ?>

<div class="admin-content">
  <main>
    <h1>Manage Orders</h1>

    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer</th>
          <th>Total</th>
          <th>Status</th>
          <th>Order Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($orders) > 0): ?>
          <?php while($o = mysqli_fetch_assoc($orders)): ?>
            <tr>
              <td>#<?= $o['id']; ?></td>
              <td><?= htmlspecialchars($o['customer_name'] ?? 'Guest'); ?></td>
              <td>$<?= number_format($o['total_amount'], 2); ?></td>
              <td><span class="status <?= $o['status']; ?>"><?= $o['status']; ?></span></td>
              <td><?= $o['order_date']; ?></td>
              <td>
                <form method="POST" class="update-form" style="display:inline;">
                  <input type="hidden" name="order_id" value="<?= $o['id']; ?>">
                  <select name="status">
                    <option value="Pending"   <?= $o['status']=='Pending'?'selected':''; ?>>Pending</option>
                    <option value="Shipped"   <?= $o['status']=='Shipped'?'selected':''; ?>>Shipped</option>
                    <option value="Delivered" <?= $o['status']=='Delivered'?'selected':''; ?>>Delivered</option>
                  </select>
                  <button type="submit" name="update_status">Update</button>
                </form>

                <!-- View details (opens user-facing order_details in a new tab) -->
                <a class="view-link" href="../order_details.php?id=<?= $o['id']; ?>" target="_blank">View Details</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6">No orders found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</div>

</body>
</html>


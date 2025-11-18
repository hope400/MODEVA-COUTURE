<?php
include '../components/connect.php';
session_start();

// ✅ Secure access
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: admin_login.php');
  exit;
}

// Default values
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate   = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Base query — now shows ALL orders
$query = "SELECT * FROM orders WHERE 1";

// Apply date filters
if (!empty($startDate) && !empty($endDate)) {
  $query .= " AND DATE(order_date) BETWEEN '$startDate' AND '$endDate'";
}

$query .= " ORDER BY order_date DESC";
$orders = mysqli_query($conn, $query);

// Calculate totals
$totalRevenue = 0;
$totalOrders = 0;
$deliveredCount = 0;

if (mysqli_num_rows($orders) > 0) {
  while ($row = mysqli_fetch_assoc($orders)) {
    $totalRevenue += $row['total_amount'];
    $totalOrders++;
    if (strtolower($row['status']) == 'delivered') {
      $deliveredCount++;
    }
  }
}
// Reset pointer for display
mysqli_data_seek($orders, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Reports | Modeva Admin</title>
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

    .filters {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 25px;
      flex-wrap: wrap;
    }

    input[type="date"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    button.filter-btn {
      background: #f0b61f;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: 0.3s;
    }

    button.filter-btn:hover {
      background: #d39c10;
    }

    .summary {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      margin: 30px 0;
      text-align: center;
    }

    .summary-card {
      background: #fff7e0;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.05);
      flex: 1 1 250px;
      margin: 10px;
    }

    .summary-card h3 {
      color: #333;
      font-size: 1.1rem;
    }

    .summary-card p {
      font-size: 1.5rem;
      font-weight: 600;
      color: #f0b61f;
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

    .Pending { color: #e67e22; font-weight: 600; }
    .Shipped { color: #3498db; font-weight: 600; }
    .Delivered { color: #27ae60; font-weight: 600; }

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

    @media (max-width:768px){
      .toggle-btn{display:block;}
      .admin-sidebar{left:-240px;transition:0.3s;}
      .admin-sidebar.active{left:0;}
      .admin-content{margin-left:0;padding-top:60px;}
      .summary{flex-direction:column;align-items:center;}
    }
  </style>
</head>
<body>

<!-- Sidebar toggle for mobile -->
<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

<?php include 'admin_sidebar.php'; ?>

<div class="admin-content">
  <main>
    <h1>Sales Report</h1>

    <form class="filters" method="GET" action="">
      <div>
        <label>From:</label>
        <input type="date" name="start_date" value="<?= htmlspecialchars($startDate); ?>">
      </div>
      <div>
        <label>To:</label>
        <input type="date" name="end_date" value="<?= htmlspecialchars($endDate); ?>">
      </div>
      <button type="submit" class="filter-btn">Filter</button>
    </form>

    <div class="summary">
      <div class="summary-card">
        <h3>Total Orders</h3>
        <p><?= $totalOrders; ?></p>
      </div>
      <div class="summary-card">
        <h3>Total Revenue</h3>
        <p>$<?= number_format($totalRevenue, 2); ?></p>
      </div>
      <div class="summary-card">
        <h3>Delivered Orders</h3>
        <p><?= $deliveredCount; ?></p>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer ID</th>
          <th>Total ($)</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($orders) > 0): ?>
          <?php while($o = mysqli_fetch_assoc($orders)): ?>
            <tr>
              <td>#<?= $o['id']; ?></td>
              <td><?= $o['user_id']; ?></td>
              <td>$<?= number_format($o['total_amount'], 2); ?></td>
              <td class="<?= ucfirst($o['status']); ?>"><?= $o['status']; ?></td>
              <td><?= $o['order_date']; ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5">No sales data for the selected period.</td></tr>
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


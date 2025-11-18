<?php
include 'components/connect.php';
session_start();

// Temporary: if login not yet implemented
if (!isset($_SESSION['user_id'])) {
  $_SESSION['user_id'] = 1;
}

$userId = $_SESSION['user_id'];
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$userId' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders | Modeva Couture</title>
  <link rel="stylesheet" href="css/user_style.css">
  <style>
    body{font-family:'Poppins',sans-serif;background:#fafafa;}
    main.orders-container{
      max-width:1000px;margin:150px auto 80px auto;padding:30px;background:#fff;
      border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,0.08);
    }
    h1{text-align:center;font-size:1.8rem;margin-bottom:25px;}
    table{width:100%;border-collapse:collapse;}
    th,td{border-bottom:1px solid #ddd;padding:10px;text-align:center;}
    th{background:#f9f9f9;font-weight:600;}
    .status-pending{color:#e67e22;font-weight:600;}
    .status-shipped{color:#2980b9;font-weight:600;}
    .status-delivered{color:#27ae60;font-weight:600;}
    .details-btn{
      background:#f0b61f;color:#fff;border:none;padding:6px 14px;border-radius:6px;
      cursor:pointer;transition:background .3s;
    }
    .details-btn:hover{background:#d39c10;}
  </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<main class="orders-container">
  <h1>My Orders</h1>

  <table>
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Date</th>
        <th>Total</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if(mysqli_num_rows($orders) > 0){
        while($o = mysqli_fetch_assoc($orders)){
          $statusClass = strtolower($o['status']);
          echo "
          <tr>
            <td>#{$o['id']}</td>
            <td>{$o['order_date']}</td>
            <td>$".number_format($o['total_amount'],2)."</td>
            <td class='status-{$statusClass}'>{$o['status']}</td>
            <td><a href='order_details.php?id={$o['id']}' class='details-btn'>View</a></td>
          </tr>";
        }
      } else {
        echo "<tr><td colspan='5'>You have no orders yet.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</main>

<?php include 'components/user_footer.php'; ?>
</body>
</html>

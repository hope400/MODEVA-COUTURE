<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  $_SESSION['user_id'] = 1; // temporary for testing
}

$userId = $_SESSION['user_id'];
$orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ✅ Handle "Order Again" submission
if (isset($_POST['reorder'])) {
  $reorderId = intval($_POST['reorder']);

  // Find the original order
  $originalOrder = mysqli_query($conn, "SELECT * FROM orders WHERE id='$reorderId' AND user_id='$userId' LIMIT 1");
  if (mysqli_num_rows($originalOrder) > 0) {
    $original = mysqli_fetch_assoc($originalOrder);
    $orderDateStr = $original['order_date'];

    // Fetch all items from that checkout (same date)
    $items = mysqli_query($conn, "
      SELECT * FROM orders 
      WHERE user_id='$userId' AND order_date='$orderDateStr'
    ");

    while ($item = mysqli_fetch_assoc($items)) {
      $productId = $item['product_id'];
      $quantity  = $item['quantity'];
      $size      = mysqli_real_escape_string($conn, $item['size']);
      $status    = 'Pending';

      // Get latest product price
      $priceQuery = mysqli_query($conn, "SELECT price FROM products WHERE id='$productId' LIMIT 1");
      $priceData  = mysqli_fetch_assoc($priceQuery);
      $total = $priceData['price'] * $quantity;

      // Insert new order
      mysqli_query($conn, "
        INSERT INTO orders (user_id, product_id, quantity, size, total_amount, status, order_date)
        VALUES ('$userId', '$productId', '$quantity', '$size', '$total', '$status', NOW())
      ");
    }

    echo "<script>alert('✅ Order placed again successfully!'); window.location='order.php';</script>";
    exit;
  } else {
    echo "<script>alert('⚠️ Could not find this order to reorder.');</script>";
  }
}

// Fetch the specific order you clicked
$orderQuery = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$orderId' LIMIT 1");
if (!$orderQuery || mysqli_num_rows($orderQuery) == 0) {
  die("<h2 style='text-align:center;margin-top:100px;'>Order not found.</h2>");
}
$order = mysqli_fetch_assoc($orderQuery);

// Delivery tracker
$orderDate = new DateTime($order['order_date']);
$today = new DateTime();
$interval = $today->diff($orderDate);
$daysPassed = $interval->days;
$daysRemaining = max(0, 7 - $daysPassed);
$status = $daysPassed >= 7 ? 'Delivered' : $order['status'];

// Auto update DB if delivered
if ($status === 'Delivered' && $order['status'] !== 'Delivered') {
  mysqli_query($conn, "UPDATE orders SET status='Delivered' WHERE id='$orderId'");
}

// Fetch all products in this checkout
$orderDateStr = $order['order_date'];
$orderItemsQuery = mysqli_query($conn, "
  SELECT 
    oi.*, 
    p.name AS product_name, 
    p.image AS product_image, 
    p.price AS product_price
  FROM order_items oi
  JOIN products p ON oi.product_id = p.id
  WHERE oi.order_id = '$orderId'
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order #<?= $order['id']; ?> | Modeva Couture</title>
  <link rel="stylesheet" href="css/user_style.css">
  <style>
    body{font-family:'Poppins',sans-serif;background:#fafafa;}
    main{
      max-width:950px;margin:150px auto;background:#fff;padding:40px;
      border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.08);
    }
    h1{text-align:center;margin-bottom:20px;}
    p{margin:6px 0;color:#333;}
    .status{display:inline-block;padding:6px 14px;border-radius:8px;font-weight:600;text-transform:capitalize;}
    .pending{background:#fce5cd;color:#e67e22;}
    .delivered{background:#d5f5e3;color:#27ae60;}
    .tracker{margin:20px 0;background:#eee;border-radius:10px;overflow:hidden;height:20px;}
    .progress{height:100%;background:#f0b61f;text-align:center;color:#fff;
      font-size:.9rem;line-height:20px;transition:width .4s ease;}
    table{width:100%;border-collapse:collapse;margin-top:20px;}
    th,td{border-bottom:1px solid #ddd;padding:12px;text-align:center;vertical-align:middle;}
    th{background:#f9f9f9;}
    img.product-img{width:80px;height:80px;object-fit:cover;border-radius:8px;}

    /* ✅ Buttons styling */
    .back-btn, .order-again-btn {
      background:#f0b61f;
      color:#fff;
      padding:10px 25px;
      border-radius:6px;
      border:none;
      text-decoration:none;
      cursor:pointer;
      font-size:15px;
      font-weight:500;
      transition:0.3s;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:5px;
      min-width:160px;
      text-align:center;
    }
    .back-btn:hover, .order-again-btn:hover {
      background:#d39c10;
    }

    /* ✅ Keep buttons on the same line */
    .action-buttons {
      display:flex;
      justify-content:center;
      align-items:center;
      gap:20px;
      margin-top:30px;
      flex-wrap:nowrap; /* keeps them side by side */
    }

    @media(max-width:600px){
      .action-buttons{flex-wrap:wrap;} /* stack on small screens */
    }
  </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<main>
  <h1>Order #<?= $order['id']; ?></h1>

  <p><strong>Placed on:</strong> <?= $order['order_date']; ?></p>
  <p><strong>Status:</strong> 
     <span class="status <?= strtolower($status); ?>"><?= $status; ?></span>
  </p>
  <p><strong>Total Amount:</strong> $<?= number_format($order['total_amount'], 2); ?></p>

  <!-- Delivery Tracker -->
  <?php if($status !== 'Delivered'): ?>
    <h3 style="margin-top:25px;">Estimated Delivery in 7 Business Days</h3>
    <?php 
      $progress = min(100, ($daysPassed / 7) * 100);
      echo "
      <div class='tracker'>
        <div class='progress' style='width: {$progress}%'>
          {$daysPassed} / 7 days
        </div>
      </div>
      <p style='text-align:center;color:#555;'>Approx. {$daysRemaining} days remaining for delivery</p>
      ";
    ?>
  <?php else: ?>
    <div class="tracker">
      <div class="progress" style="width:100%;background:#27ae60;">Delivered</div>
    </div>
  <?php endif; ?>

  <h3 style="margin-top:25px;">Ordered Items</h3>
  <table>
    <thead>
      <tr>
        <th>Image</th>
        <th>Product</th>
        <th>Size</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $grandTotal = 0;
      while($item = mysqli_fetch_assoc($orderItemsQuery)){
        $subtotal = $item['product_price'] * $item['quantity'];
        $grandTotal += $subtotal;
        echo "
        <tr>
          <td><img src='image/{$item['product_image']}' class='product-img' alt='{$item['product_name']}'></td>
          <td>{$item['product_name']}</td>
          <td>{$item['size']}</td>
          <td>{$item['quantity']}</td>
          <td>$".number_format($item['product_price'],2)."</td>
          <td>$".number_format($subtotal,2)."</td>
        </tr>";
      }
      ?>
    </tbody>
  </table>

  <p style="text-align:right;font-weight:600;margin-top:10px;">
    Grand Total: $<?= number_format($grandTotal, 2); ?>
  </p>

  <!-- ✅ Centered and aligned buttons -->
  <div class="action-buttons">
    <a href="order.php" class="back-btn">← Back to Orders</a>
    <form method="POST" style="display:inline;">
      <input type="hidden" name="reorder" value="<?= $order['id']; ?>">
      <button type="submit" class="order-again-btn">Order Again</button>
    </form>
  </div>
</main>

<?php include 'components/user_footer.php'; ?>
</body>
</html>

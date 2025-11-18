<?php
// --- STRICT JSON MODE: never echo anything except JSON for POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json; charset=utf-8');
  ini_set('display_errors', 0); // don't print warnings into JSON
  error_reporting(E_ALL);
}

include 'components/connect.php';
session_start();

// TEMP user for testing
if (!isset($_SESSION['user_id'])) {
  $_SESSION['user_id'] = 1;
}

// Throw exceptions on MySQL errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Parse cart
    if (!isset($_POST['cart'])) {
      echo json_encode(['success' => false, 'message' => 'Missing cart payload']);
      exit;
    }
    $cart = json_decode($_POST['cart'], true);
    if (!$cart || !is_array($cart) || count($cart) === 0) {
      echo json_encode(['success' => false, 'message' => 'Cart is empty']);
      exit;
    }

    $userId = (int)$_SESSION['user_id'];

    // 2) SCHEMA GUARD (quick checks that prevent mysterious SQL errors)
    // Orders must have these columns:
    //   id, user_id, total_amount, status, order_date
    // Products must have column: stock
    // Table order_items must exist with: order_id, product_id, quantity, size, price, subtotal
    // If you need to (re)create/alter, run the SQL shown below this file.

    // 3) Validate stock for every item
    $total = 0.0;
    foreach ($cart as $item) {
      $pid = (int)($item['id'] ?? 0);
      $qty = (int)($item['qty'] ?? 0);
      $price = (float)($item['price'] ?? 0);
      if ($pid <= 0 || $qty <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart item.']);
        exit;
      }
      $total += $price * $qty;

      $res = mysqli_query($conn, "SELECT name, stock FROM products WHERE id={$pid} LIMIT 1");
      $p = mysqli_fetch_assoc($res);
      if (!$p) {
        echo json_encode(['success' => false, 'message' => "Product #{$pid} not found."]);
        exit;
      }
      if ((int)$p['stock'] < $qty) {
        echo json_encode([
          'success' => false,
          'message' => "⚠️ Only {$p['stock']} units of '{$p['name']}' are left in stock."
        ]);
        exit;
      }
    }

    // 4) Create order
    $stmtOrder = mysqli_prepare(
      $conn,
      "INSERT INTO orders (user_id, total_amount, status, order_date) VALUES (?, ?, 'Pending', NOW())"
    );
    mysqli_stmt_bind_param($stmtOrder, 'id', $userId, $total);
    mysqli_stmt_execute($stmtOrder);
    $orderId = mysqli_insert_id($conn);

    // 5) Insert items + deduct stock
    $stmtItem = mysqli_prepare(
      $conn,
      "INSERT INTO order_items (order_id, product_id, quantity, size, price, subtotal)
       VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmtStock = mysqli_prepare(
      $conn,
      "UPDATE products SET stock = GREATEST(stock - ?, 0) WHERE id = ?"
    );

    foreach ($cart as $item) {
      $pid = (int)$item['id'];
      $qty = (int)$item['qty'];
      $size = isset($item['size']) ? (string)$item['size'] : '';
      $price = (float)$item['price'];
      $sub = $price * $qty;

      mysqli_stmt_bind_param($stmtItem, 'iiisdd', $orderId, $pid, $qty, $size, $price, $sub);
      mysqli_stmt_execute($stmtItem);

      mysqli_stmt_bind_param($stmtStock, 'ii', $qty, $pid);
      mysqli_stmt_execute($stmtStock);
    }

    echo json_encode(['success' => true, 'orderId' => $orderId]);
    exit;
  }
} catch (mysqli_sql_exception $e) {
  // Clean JSON error back to the client
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
  } else {
    // For GET view, show a simple message
    echo "<h3 style='margin:2rem auto;max-width:800px;font-family:sans-serif;color:#900;'>Database error: "
       . htmlspecialchars($e->getMessage()) . "</h3>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checkout | Modeva Couture</title>
  <link rel="stylesheet" href="css/user_style.css" />
  <style>
    body{font-family:'Poppins',sans-serif;background:#fafafa;}
    main.checkout-box{
      max-width:1000px;margin:150px auto 80px auto;padding:40px;
      background:#fff;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.08);
    }
    h1{text-align:center;font-size:1.8rem;margin-bottom:25px;}
    form.checkout-form{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
    label{display:block;font-weight:500;margin-bottom:5px;}
    input,select,textarea{
      width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:1rem;
    }
    textarea{resize:none;height:90px;}
    .order-summary{grid-column:1/3;margin-top:30px;background:#f9f9f9;border-radius:8px;padding:20px;}
    table{width:100%;border-collapse:collapse;}
    th,td{border-bottom:1px solid #ddd;padding:10px;text-align:center;}
    th{background:#f5f5f5;}
    .total-line{text-align:right;font-weight:600;padding-top:10px;}
    button.place-order{
      grid-column:1/3;margin-top:25px;background:#f0b61f;color:#fff;border:none;
      padding:14px;border-radius:8px;cursor:pointer;font-size:1.1rem;
    }
    button.place-order:hover{background:#d39c10;}
  </style>
</head>
<body>
<?php include 'components/user_header.php'; ?>

<main class="checkout-box">
  <h1>Checkout</h1>

  <form id="checkoutForm" class="checkout-form" onsubmit="return placeOrder(event)">
    <div>
      <label>Full Name</label>
      <input type="text" id="name" required>
      <label>Email</label>
      <input type="email" id="email" required>
      <label>Phone</label>
      <input type="text" id="phone" required>
      <label>Address</label>
      <textarea id="address" required></textarea>
    </div>

    <div>
      <label>Payment Method</label>
      <select id="payment" required>
        <option value="">Select Payment Option</option>
        <option value="cod">Cash on Delivery</option>
        <option value="card">Credit/Debit Card (Test)</option>
      </select>
      <div id="cardDetails" style="display:none;margin-top:10px;">
        <label>Card Number</label>
        <input type="text" placeholder="1234 5678 9012 3456">
        <label>Expiry Date</label>
        <input type="text" placeholder="MM/YY">
        <label>CVV</label>
        <input type="password" placeholder="***">
      </div>
    </div>

    <div class="order-summary">
      <h3>Order Summary</h3>
      <table id="orderTable">
        <thead>
          <tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
        </thead>
        <tbody></tbody>
      </table>
      <p class="total-line" id="orderTotal"></p>
    </div>

    <button type="submit" class="place-order">Place Order</button>
  </form>
</main>

<?php include 'components/user_footer.php'; ?>

<script>
function getCart(){return JSON.parse(localStorage.getItem('cart')||'[]');}
function clearCart(){localStorage.removeItem('cart');}

function loadSummary(){
  const cart=getCart(), body=document.querySelector('#orderTable tbody');
  let total=0; body.innerHTML='';
  if(cart.length===0){ body.innerHTML='<tr><td colspan="4">No items in cart.</td></tr>'; }
  for(const i of cart){
    const sub=i.price*i.qty; total+=sub;
    body.innerHTML+=`<tr><td>${i.name}</td><td>${i.qty}</td><td>$${i.price.toFixed(2)}</td><td>$${sub.toFixed(2)}</td></tr>`;
  }
  document.getElementById('orderTotal').innerText='Total: $'+total.toFixed(2);
}
loadSummary();

document.getElementById('payment').addEventListener('change',function(){
  document.getElementById('cardDetails').style.display=this.value==='card'?'block':'none';
});

function placeOrder(e){
  e.preventDefault();
  const cart=getCart();
  if(cart.length===0){ alert('Your cart is empty!'); return false; }

  const data=new FormData();
  data.append('cart', JSON.stringify(cart));

  fetch('checkout.php', { method:'POST', body:data })
    .then(async r => {
      const txt = await r.text();
      try { return JSON.parse(txt); } 
      catch(e){ throw new Error(txt || 'Non-JSON response'); }
    })
    .then(res => {
      if(res.success){
        clearCart();
        alert('✅ Thank you! Your order has been placed.');
        window.location.href='thankyou.php';
      } else {
        alert(res.message || '❌ Something went wrong.');
      }
    })
    .catch(err => {
      console.error(err);
      alert('Server error: ' + (err.message || 'Please try again.'));
    });
}
</script>
</body>
</html>

<?php
include 'components/connect.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modeva Couture | My Cart</title>

  <!-- 🧷 Link your existing main CSS and libraries -->
  <link rel="stylesheet" href="css/user_style.css">
  <link rel="stylesheet" href="slick.css">
  <link rel="stylesheet" href="slick-theme.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* ---- PAGE LAYOUT ---- */
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fafafa;
    }

    main.cart-container {
      max-width: 1100px;
      margin: 160px auto 100px auto;
      padding: 30px 40px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .cart-header {
      font-size: 1.8rem;
      font-weight: 600;
      margin-bottom: 25px;
      text-align: center;
      color: #333;
      border-bottom: 3px solid #f0b61f;
      padding-bottom: 10px;
    }

    table.cart {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table.cart th, table.cart td {
      border-bottom: 1px solid #ddd;
      padding: 14px 12px;
      text-align: center;
      vertical-align: middle;
    }

    table.cart th {
      background: #f9f9f9;
      color: #333;
      font-weight: 600;
    }

    table.cart img {
      width: 70px;
      height: 70px;
      border-radius: 8px;
      object-fit: cover;
      border: 1px solid #eee;
    }

    .cart-summary {
      text-align: right;
      margin-top: 25px;
    }

    .cart-summary p {
      font-size: 1.2rem;
      margin: 8px 0;
      font-weight: 500;
    }

    .cart-summary button {
      background: #f0b61f;
      color: #fff;
      border: none;
      padding: 12px 25px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
      transition: background 0.3s ease;
    }

    .cart-summary button:hover {
      background: #d39c10;
    }

    .qty-input {
      width: 60px;
      padding: 5px;
      text-align: center;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .remove-btn {
      background: none;
      border: none;
      color: crimson;
      font-size: 1.1rem;
      cursor: pointer;
      transition: color 0.2s ease;
    }

    .remove-btn:hover {
      color: darkred;
    }

    /* footer spacing fix */
    footer {
      margin-top: 80px;
    }

    @media (max-width: 768px) {
      main.cart-container {
        margin: 120px 15px;
        padding: 20px;
      }
      table.cart th, table.cart td {
        font-size: 0.9rem;
        padding: 10px;
      }
    }
  </style>
</head>
<body>

  <!-- ✅ Include site header -->
  <?php include 'components/user_header.php'; ?>

  <!-- ✅ CART SECTION -->
  <main class="cart-container">
    <h1 class="cart-header">Your Shopping Cart</h1>

    <table class="cart" id="cartTable">
      <thead>
        <tr>
          <th>Product</th>
          <th>Image</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Remove</th>
        </tr>
      </thead>
      <tbody id="cartBody">
        <!-- JS will fill items here -->
      </tbody>
    </table>

    <div class="cart-summary">
      <p id="cartTotal">Total: $0.00</p>
      <button id="checkoutBtn">Proceed to Checkout</button>
    </div>
  </main>

  <!-- ✅ Include site footer -->
  <?php include 'components/user_footer.php'; ?>

  <!-- 🧩 JS Files -->
  <script src="js/cart.js"></script>
  <script src="js/jquary.js"></script>
  <script src="js/slick.js"></script>
  <script src="js/script.js"></script>

  <!-- ✅ Cart counter logic -->
  <script>
function getCart() {
  return JSON.parse(localStorage.getItem('cart') || '[]');
}

function saveCart(cart) {
  localStorage.setItem('cart', JSON.stringify(cart));
}

function loadCart() {
  const cart = getCart();
  const tbody = document.getElementById('cartBody');
  tbody.innerHTML = '';

  if (cart.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="6" style="padding:40px 0;">
          <div style="text-align:center;color:#777;">
            <i class="bx bx-cart" style="font-size:3rem;color:#ccc;"></i>
            <p>Your cart is empty.</p>
            <a href="menu.php" class="btn small-btn" style="background:#f0b61f;color:#fff;border-radius:5px;padding:8px 20px;">Continue Shopping</a>
          </div>
        </td>
      </tr>`;
    document.getElementById('cartTotal').innerText = 'Total: $0.00';
    updateCartCount();
    return;
  }

  let total = 0;

  for (const item of cart) {
    const subtotal = item.price * item.qty;
    total += subtotal;

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${item.name}</td>
      <td><img src="${item.image}" alt="${item.name}" style="width:70px;height:70px;border-radius:8px;object-fit:cover"></td>
      <td>$${item.price.toFixed(2)}</td>
      <td>
        <input type="number" min="1" value="${item.qty}" class="qty-input"
               onchange="updateQty('${item.id}', this.value)">
      </td>
      <td>$${subtotal.toFixed(2)}</td>
      <td><button class="remove-btn" onclick="removeItem('${item.id}')">✖</button></td>
    `;
    tbody.appendChild(tr);
  }

  document.getElementById('cartTotal').innerText = `Total: $${total.toFixed(2)}`;
  updateCartCount();
}

function updateQty(id, qty) {
  qty = parseInt(qty);
  if (isNaN(qty) || qty < 1) qty = 1;

  const cart = getCart();
  const item = cart.find(i => i.id === id);
  if (item) item.qty = qty;

  saveCart(cart);
  loadCart();
}

function removeItem(id) {
  let cart = getCart().filter(i => i.id !== id);
  saveCart(cart);
  loadCart();
}

function updateCartCount() {
  const cart = getCart();
  const count = cart.reduce((sum, i) => sum + (i.qty || 1), 0);
  const badge = document.querySelector('.cart-count');
  if (badge) badge.textContent = count > 0 ? count : '';
}

// Run once page loads
document.addEventListener('DOMContentLoaded', loadCart);
</script>

<script>
// ✅ Proceed to checkout logic
document.getElementById('checkoutBtn').addEventListener('click', () => {
  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  if (cart.length === 0) {
    alert('🛒 Your cart is empty! Please add some items before checking out.');
    return;
  }
  window.location.href = 'checkout.php';
});
</script>


</body>
</html>

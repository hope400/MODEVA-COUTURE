<?php
include 'components/connect.php';
session_start();

// Temporary test user
if (!isset($_SESSION['user_id'])) {
  $_SESSION['user_id'] = 1;
}

$userId = $_SESSION['user_id'];

// ✅ Handle adding from menu
if (isset($_POST['add_to_wishlist'])) {
  $productId = intval($_POST['product_id']);
  $check = mysqli_query($conn, "SELECT * FROM wishlist WHERE user_id='$userId' AND product_id='$productId'");
  if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "INSERT INTO wishlist (user_id, product_id) VALUES ('$userId', '$productId')");
    echo "<script>alert('❤️ Added to your wishlist!');</script>";
  } else {
    echo "<script>alert('⚠️ Already in your wishlist.');</script>";
  }
}

// ✅ Handle removing
if (isset($_GET['remove'])) {
  $wishlistId = intval($_GET['remove']);
  mysqli_query($conn, "DELETE FROM wishlist WHERE id='$wishlistId'");
  echo "<script>alert('❌ Item removed.');window.location='wishlist.php';</script>";
}

// ✅ Fetch wishlist items
$wishlistItems = mysqli_query($conn, "
  SELECT w.id AS wishlist_id, p.name, p.price, p.image
  FROM wishlist w
  JOIN products p ON w.product_id = p.id
  WHERE w.user_id = '$userId'
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Wishlist | Modeva Couture</title>
  <link rel="stylesheet" href="css/user_style.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <style>
    body{font-family:'Poppins',sans-serif;background:#fafafa;}
    main{
      max-width:1000px;margin:150px auto 80px auto;padding:40px;background:#fff;
      border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.08);
    }
    h1{text-align:center;font-size:1.8rem;margin-bottom:25px;}
    table{width:100%;border-collapse:collapse;}
    th,td{border-bottom:1px solid #ddd;padding:12px;text-align:center;}
    th{background:#f9f9f9;}
    img{width:90px;height:90px;border-radius:8px;object-fit:cover;}
    .btn-register{
      background:var(--main-color);
      color:#fff;
      padding:8px 16px;
      border-radius:25px;
      text-decoration:none;
      font-size:14px;
      font-weight:500;
      transition:0.3s;
      border:none;
      cursor:pointer;
      display:inline-flex;
      align-items:center;
      gap:6px;
      justify-content:center;
    }
    .btn-register:hover{background:#d39c10;}
    .empty-msg{text-align:center;color:#777;padding:30px 0;}
    td.actions{display:flex;justify-content:center;gap:10px;flex-wrap:wrap;}
  </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<main>
  <h1>My Wishlist</h1>

  <table>
    <thead>
      <tr>
        <th>Image</th>
        <th>Product</th>
        <th>Price</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($wishlistItems && mysqli_num_rows($wishlistItems) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($wishlistItems)): ?>
          <tr>
            <td><img src="image/<?= $row['image']; ?>" alt="<?= $row['name']; ?>"></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td>$<?= number_format($row['price'], 2); ?></td>
            <td class="actions">
              <!-- 🛒 Move to Cart -->
              <button onclick="moveToCart(
                  '<?= addslashes($row['name']); ?>',
                  '<?= $row['price']; ?>',
                  'image/<?= $row['image']; ?>',
                  '<?= $row['wishlist_id']; ?>'
                )"
                class="btn-register">
                <i class="bx bx-cart"></i> Move to Cart
              </button>

              <!-- ❌ Remove -->
              <a href="wishlist.php?remove=<?= $row['wishlist_id']; ?>" 
                 class="btn-register" style="background:crimson;">
                 <i class="bx bx-x"></i> Remove
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="4" class="empty-msg">💔 Your wishlist is empty.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</main>

<?php include 'components/user_footer.php'; ?>

<!-- 🧠 JavaScript for Move to Cart -->
<script>
function getCart() {
  return JSON.parse(localStorage.getItem('cart') || '[]');
}
function saveCart(cart) {
  localStorage.setItem('cart', JSON.stringify(cart));
}
function moveToCart(name, price, image, wishlistId) {
  let cart = getCart();
  const existing = cart.find(item => item.name === name);

  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({
      id: Date.now().toString(),
      name: name,
      price: parseFloat(price),
      image: image,
      qty: 1
    });
  }

  saveCart(cart);
  alert(`${name} has been added to your cart 🛒`);
  window.location.href = `wishlist.php?remove=${wishlistId}`;
}
</script>
</body>
</html>


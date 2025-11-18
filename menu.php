<?php
include 'components/connect.php';
session_start();

// Get filters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$size = isset($_GET['size']) ? $_GET['size'] : '';
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// Build query — now joined with discounts table
$query = "
  SELECT 
    p.*,
    d.discount_type,
    d.discount_value,
    d.start_date,
    d.end_date
  FROM products p
  LEFT JOIN discounts d 
    ON p.id = d.product_id
    AND CURDATE() BETWEEN d.start_date AND d.end_date
  WHERE 1
";

// Apply filters
if (!empty($category)) {
  $query .= " AND LOWER(p.category) = LOWER('$category')";
}

if (!empty($size)) {
  $query .= " AND LOWER(p.size) = LOWER('$size')";
}

if (!empty($search)) {
  $searchSafe = mysqli_real_escape_string($conn, $search);
  $query .= " AND (LOWER(p.name) LIKE LOWER('%$searchSafe%') OR LOWER(p.description) LIKE LOWER('%$searchSafe%'))";
}

$query .= " ORDER BY p.id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop | Modeva Couture</title>
  <link rel="stylesheet" href="css/user_style.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <style>
    .price {
      font-size: 1.1rem;
      font-weight: 600;
      color: #333;
    }
    .old-price {
      text-decoration: line-through;
      color: #888;
      margin-right: 6px;
    }
    .discount-badge {
      background: #27ae60;
      color: #fff;
      padding: 2px 6px;
      border-radius: 5px;
      font-size: 0.8rem;
      margin-left: 5px;
    }
  </style>
</head>

<body>

<?php include 'components/user_header.php'; ?>

<section class="shop">
  <h2>Modeva Couture Collection</h2>

  <!-- ===== SIZE GUIDE ===== -->
  <section class="size-guide">
    <h3>Size Guide. Find Your Perfect Fit</h3>
    <p class="guide-note">
      Measure yourself and compare with our chart below to ensure the best fit for your Modéva Couture pieces.
    </p>

    <div class="size-grid">
      <div class="size-row size-header">
        <span>Size</span><span>Bust (cm)</span><span>Waist (cm)</span><span>Hips (cm)</span>
      </div>
      <div class="size-row"><span>S</span><span>82–86</span><span>64–68</span><span>88–92</span></div>
      <div class="size-row"><span>M</span><span>87–92</span><span>69–74</span><span>93–98</span></div>
      <div class="size-row"><span>L</span><span>93–99</span><span>75–81</span><span>99–105</span></div>
      <div class="size-row"><span>XL</span><span>100–106</span><span>82–88</span><span>106–112</span></div>
    </div>

    <p class="guide-footer">
      Measurements shown are body measurements. If you’re between sizes, we recommend sizing up for comfort.
    </p>
  </section>

  <!-- Filter Bar -->
  <form class="filters" method="GET" action="">
    <select name="category" onchange="this.form.submit()">
      <option value="">All Categories</option>
      <option value="Women" <?= $category=='Women'?'selected':''; ?>>Women</option>
      <option value="Men" <?= $category=='Men'?'selected':''; ?>>Men</option>
      <option value="Kids" <?= $category=='Kids'?'selected':''; ?>>Kids</option>
    </select>

    <select name="size" onchange="this.form.submit()">
      <option value="">All Sizes</option>
      <option value="S" <?= $size=='S'?'selected':''; ?>>S</option>
      <option value="M" <?= $size=='M'?'selected':''; ?>>M</option>
      <option value="L" <?= $size=='L'?'selected':''; ?>>L</option>
      <option value="XL" <?= $size=='XL'?'selected':''; ?>>XL</option>
    </select>
  </form>

  <!-- Product Grid -->
  <div class="product-grid">
    <?php while($row = mysqli_fetch_assoc($result)): ?>
      <?php
        $originalPrice = $row['price'];
        $discountPrice = $originalPrice;
        $discountText = '';

        // 🧮 Calculate discount if active
        if (!empty($row['discount_type']) && $row['discount_value'] > 0) {
          if ($row['discount_type'] === 'percentage') {
            $discountPrice = $originalPrice - ($originalPrice * ($row['discount_value'] / 100));
            $discountText = "-{$row['discount_value']}%";
          } else {
            $discountPrice = max(0, $originalPrice - $row['discount_value']);
            $discountText = "-$" . number_format($row['discount_value'], 2);
          }
        }
      ?>

      <div class="product-card">
        <img src="image/<?= $row['image']; ?>" alt="<?= htmlspecialchars($row['name']); ?>">
        <h3><?= htmlspecialchars($row['name']); ?></h3>
        <p class="desc"><?= htmlspecialchars($row['description']); ?></p>

        <!-- 💰 Price section -->
        <?php if ($discountPrice < $originalPrice): ?>
          <p class="price">
            <span class="old-price">$<?= number_format($originalPrice, 2); ?></span>
            <span>$<?= number_format($discountPrice, 2); ?></span>
            <span class="discount-badge"><?= $discountText; ?></span>
          </p>
        <?php else: ?>
          <p class="price">$<?= number_format($originalPrice, 2); ?></p>
        <?php endif; ?>

        <p class="size">Size: <?= htmlspecialchars($row['size']); ?></p>

        <div class="buttons">
          <!-- ❤️ Wishlist -->
          <form action="wishlist.php" method="POST" style="display:inline;">
            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
            <button type="submit" name="add_to_wishlist"
              class="btn-register"
              style="border:none;cursor:pointer;display:inline-flex;
                     align-items:center;gap:6px;">
              <i class="bx bx-heart"></i> Wishlist
            </button>
          </form>

          <!-- 🛒 Add to Cart -->
          <?php if ($row['stock'] > 0): ?>
            <a href="javascript:void(0);"
               onclick="addToCart(
                 '<?= addslashes($row['id']); ?>',
                 '<?= addslashes($row['name']); ?>',
                 '<?= $discountPrice; ?>',
                 'image/<?= $row['image']; ?>'
               )"
               class="btn-register"
               style="display:inline-flex;align-items:center;gap:6px;text-decoration:none;">
               <i class="bx bx-cart"></i> Add to Cart
            </a>
          <?php else: ?>
            <button disabled
              style="background:#ccc;color:#666;padding:8px 15px;border:none;border-radius:25px;cursor:not-allowed;">
              ❌ Sold Out
            </button>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<?php include 'components/user_footer.php'; ?>

<!-- 🧠 Add JS for cart functionality -->
<script>
function getCart() {
  return JSON.parse(localStorage.getItem('cart') || '[]');
}
function saveCart(cart) {
  localStorage.setItem('cart', JSON.stringify(cart));
}
function addToCart(id, name, price, image) {
  let cart = getCart();
  const existing = cart.find(item => item.id === id);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ id: id, name: name, price: parseFloat(price), image: image, qty: 1 });
  }
  saveCart(cart);
  alert(`${name} added to your cart!`);
  window.location.href = 'cart.php';
}
</script>

</body>
</html>


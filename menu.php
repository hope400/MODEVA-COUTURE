<?php
include 'components/connect.php';

session_start();

// Get filters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$size = isset($_GET['size']) ? $_GET['size'] : '';
$search = isset($_GET['q']) ? trim($_GET['q']) : '';


// Build query
$query = "SELECT * FROM products WHERE 1";

// Apply category filter
if (!empty($category)) {
  $query .= " AND LOWER(category) = LOWER('$category')";
}

// Apply size filter
if (!empty($size)) {
  $query .= " AND LOWER(size) = LOWER('$size')";
}


if (!empty($search)) {
  $searchSafe = mysqli_real_escape_string($conn, $search);
  $query .= " AND (LOWER(name) LIKE LOWER('%$searchSafe%') OR LOWER(description) LIKE LOWER('%$searchSafe%'))";
}


$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop | Modeva Couture</title>
  <link rel="stylesheet" href="css/user_style.css">
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
    <div class="size-row"><span>S (S)</span><span>82 – 86</span><span>64 – 68</span><span>88 – 92</span></div>
    <div class="size-row"><span>M (M)</span><span>87 – 92</span><span>69 – 74</span><span>93 – 98</span></div>
    <div class="size-row"><span>L (L)</span><span>93 – 99</span><span>75 – 81</span><span>99 – 105</span></div>
    <div class="size-row"><span>XL (XL)</span><span>100 – 106</span><span>82 – 88</span><span>106 – 112</span></div>
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
      <div class="product-card">
        <img src="image/<?= $row['image']; ?>" alt="<?= htmlspecialchars($row['name']); ?>">
        <h3><?= htmlspecialchars($row['name']); ?></h3>
        <p class="desc"><?= htmlspecialchars($row['description']); ?></p>
        <p class="price">$<?= number_format($row['price'], 2); ?></p>
        <p class="size">Size: <?= $row['size']; ?></p>

        <div class="buttons">
          <a href="wishlist.php?id=<?= $row['id']; ?>" class="btn small-btn"><i class="bx bx-heart"></i> Wishlist</a>
          <a href="cart.php?id=<?= $row['id']; ?>" class="btn small-btn"><i class="bx bx-cart"></i> Add to Cart</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<?php include 'components/user_footer.php'; ?>
</body>
</html>

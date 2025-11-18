<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  $_SESSION['user_id'] = 1; // temporary test user
}

$userId = $_SESSION['user_id'];

// ✅ Handle new review submission
if (isset($_POST['submit_review'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $rating = intval($_POST['rating']);
  $review = mysqli_real_escape_string($conn, $_POST['review']);

  if (!empty($name) && !empty($review) && $rating > 0) {
    mysqli_query($conn, "
      INSERT INTO reviews (user_id, name, rating, review, created_at)
      VALUES ('$userId', '$name', '$rating', '$review', NOW())
    ");
    echo "<script>alert('✅ Your review has been submitted!'); window.location='reviews.php';</script>";
    exit;
  } else {
    echo "<script>alert('⚠️ Please fill all fields and select a rating.');</script>";
  }
}

// ✅ Fetch all reviews
$reviews = mysqli_query($conn, "SELECT * FROM reviews ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Reviews | Modeva Couture</title>
  <link rel="stylesheet" href="css/user_style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fafafa;
    }

    main {
      max-width: 950px;
      margin: 150px auto 100px auto;
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    h1 {
      text-align: center;
      font-size: 1.8rem;
      margin-bottom: 25px;
    }

    .reviews {
      margin-top: 30px;
    }

    .review-card {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .review-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .review-name {
      font-weight: 600;
      font-size: 1.1rem;
    }

    .stars {
      color: #f0b61f;
      font-size: 1rem;
    }

    .review-date {
      font-size: 0.9rem;
      color: #777;
    }

    .review-text {
      color: #333;
      line-height: 1.5;
      margin-top: 5px;
    }

    .review-form {
      margin-top: 40px;
      background: #fff7e6;
      padding: 25px;
      border-radius: 10px;
      border: 1px solid #f0b61f;
    }

    label {
      display: block;
      font-weight: 500;
      margin-top: 10px;
      margin-bottom: 5px;
    }

    input[type="text"], textarea, select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-family: inherit;
      font-size: 1rem;
    }

    textarea {
      resize: none;
      height: 90px;
    }

    button {
      background: #f0b61f;
      color: #fff;
      border: none;
      padding: 10px 25px;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: 0.3s;
      margin-top: 15px;
    }

    button:hover {
      background: #d39c10;
    }

    .empty {
      text-align: center;
      color: #888;
      margin-top: 20px;
    }
  </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<main>
  <h1>Customer Reviews</h1>

  <!-- ✅ Existing Reviews -->
  <div class="reviews">
    <?php if (mysqli_num_rows($reviews) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($reviews)): ?>
        <div class="review-card">
          <div class="review-header">
            <div class="review-name"><?= htmlspecialchars($row['name']); ?></div>
            <div class="stars">
              <?= str_repeat("★", $row['rating']); ?>
              <?= str_repeat("☆", 5 - $row['rating']); ?>
            </div>
          </div>
          <div class="review-date"><?= date("F j, Y, g:i a", strtotime($row['created_at'])); ?></div>
          <div class="review-text"><?= nl2br(htmlspecialchars($row['review'])); ?></div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="empty">No reviews yet. Be the first to share your thoughts! 💬</p>
    <?php endif; ?>
  </div>

  <!-- ✅ Review Form -->
  <form action="" method="POST" class="review-form">
    <h2 style="margin-bottom:10px;">Leave a Review</h2>
    <label>Your Name</label>
    <input type="text" name="name" required>

    <label>Rating</label>
    <select name="rating" required>
      <option value="">Select</option>
      <option value="5">★★★★★ - Excellent</option>
      <option value="4">★★★★☆ - Good</option>
      <option value="3">★★★☆☆ - Average</option>
      <option value="2">★★☆☆☆ - Poor</option>
      <option value="1">★☆☆☆☆ - Terrible</option>
    </select>

    <label>Your Review</label>
    <textarea name="review" required placeholder="Share your experience with Modeva Couture..."></textarea>

    <button type="submit" name="submit_review">Post Review</button>
  </form>
</main>

<?php include 'components/user_footer.php'; ?>
</body>
</html>

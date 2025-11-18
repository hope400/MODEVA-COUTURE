<?php
include '../components/connect.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: admin_login.php');
  exit;
}

// ✅ Handle delete review
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  mysqli_query($conn, "DELETE FROM reviews WHERE id = $id");
  echo "<script>alert('🗑 Review deleted successfully!'); window.location='admin_reviews.php';</script>";
  exit;
}

// ✅ Fetch all reviews (from same table as user side)
$reviews = mysqli_query($conn, "
  SELECT r.*, 
         CONCAT(u.first_name, ' ', u.last_name) AS customer_name
  FROM reviews r
  LEFT JOIN users u ON r.user_id = u.id
  ORDER BY r.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Reviews | Admin Dashboard</title>
  <link rel="stylesheet" href="../css/user_style.css">
  <style>
    body{font-family:'Poppins',sans-serif;background:#fafafa;}
    main{
      max-width:1100px;margin:130px auto;padding:40px;background:#fff;
      border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.08);
    }
    h1{text-align:center;margin-bottom:30px;}
    table{width:100%;border-collapse:collapse;}
    th,td{border-bottom:1px solid #ddd;padding:12px;text-align:center;vertical-align:middle;}
    th{background:#f9f9f9;font-weight:600;}
    .rating{color:#f0b61f;}
    .btn{
      background:#f0b61f;color:#fff;padding:6px 14px;border:none;
      border-radius:6px;text-decoration:none;font-size:14px;
      transition:0.3s;cursor:pointer;
    }
    .btn:hover{background:#d39c10;}
    .delete-btn{background:crimson;}
    .delete-btn:hover{background:darkred;}
  </style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="admin-content">
<main>
  <h1>Customer Reviews</h1>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Reviewer</th>
        <th>Rating</th>
        <th>Review</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($reviews) > 0): ?>
        <?php while($r = mysqli_fetch_assoc($reviews)): ?>
          <tr>
            <td><?= $r['id']; ?></td>
            <td><?= htmlspecialchars($r['name'] ?? $r['customer_name'] ?? 'Guest'); ?></td>
            <td class="rating"><?= str_repeat('⭐', $r['rating']); ?></td>
            <td><?= htmlspecialchars($r['review']); ?></td>
            <td><?= date("F j, Y, g:i a", strtotime($r['created_at'])); ?></td>
            <td>
              <a href="?delete=<?= $r['id']; ?>" 
                 class="btn delete-btn"
                 onclick="return confirm('Delete this review?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6">No reviews found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</main>
</div>

</body>
</html>


<?php
include '../components/connect.php';
session_start();

//  Protect admin access
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: admin_login.php');
  exit;
}

//  Handle user deletion (optional)
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  mysqli_query($conn, "DELETE FROM users WHERE id = $id");
  echo "<script>alert('🗑 User deleted successfully!'); window.location='admin_manage_users.php';</script>";
  exit;
}

//  Fetch all users
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users | Modeva Admin</title>
  <link rel="stylesheet" href="../css/user_style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fafafa;
      margin: 0;
    }

    main {
      max-width: 1000px;
      margin: 130px auto;
      padding: 40px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,.08);
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 1.8rem;
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

    .status-success {
      color: #27ae60;
      font-weight: 600;
    }

    .status-failed {
      color: crimson;
      font-weight: 600;
    }

    .delete-btn {
      background: crimson;
      color: #fff;
      padding: 6px 12px;
      border-radius: 6px;
      text-decoration: none;
      transition: 0.3s;
    }

    .delete-btn:hover {
      background: darkred;
    }

    .toggle-btn {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      background: #f0b61f;
      color: #fff;
      border: none;
      padding: 10px 14px;
      border-radius: 6px;
      cursor: pointer;
      z-index: 1001;
    }

    @media (max-width: 768px) {
      .toggle-btn { display: block; }
      .admin-sidebar { left: -240px; transition: 0.3s; }
      .admin-sidebar.active { left: 0; }
      .admin-content { margin-left: 0; padding-top: 60px; }
      table { font-size: 0.9rem; }
    }
  </style>
</head>
<body>

<!-- Sidebar toggle for mobile -->
<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

<?php include 'admin_sidebar.php'; ?>

<div class="admin-content">
  <main>
    <h1>Manage Customer Accounts</h1>

    <table>
      <thead>
        <tr>
          <th>User ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>
          <th>Registration Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while($u = mysqli_fetch_assoc($result)): ?>
            <?php
              // Determine registration status
              $statusText = (!empty($u['email']) && !empty($u['password'])) 
                ? "<span class='status-success'>✅ Successful</span>"
                : "<span class='status-failed'>❌ Failed</span>";
            ?>
            <tr>
              <td><?= $u['id']; ?></td>
              <td><?= htmlspecialchars($u['first_name']); ?></td>
              <td><?= htmlspecialchars($u['last_name']); ?></td>
              <td><?= htmlspecialchars($u['email']); ?></td>
              <td><?= $statusText; ?></td>
              <td>
                <a href="?delete=<?= $u['id']; ?>" class="delete-btn"
                   onclick="return confirm('Are you sure you want to delete this user?');">
                  🗑 Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6">No registered users found.</td></tr>
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


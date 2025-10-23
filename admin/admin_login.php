<?php
include '../components/connect.php';
session_start();

if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  // Simple login (hardcoded credentials for now)
  if ($username === 'admin' && $password === 'admin123') {
    $_SESSION['admin_logged_in'] = true;
    header('Location: admin_dashboard.php');
    exit;
  } else {
    $error = "Invalid admin credentials!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | Modeva Couture</title>
  <link rel="stylesheet" href="../css/user_style.css">
</head>
<body class="auth-body">
  <div class="auth-container">
    <div class="auth-box">
      <h2>Admin Login</h2>
      <?php if (isset($error)): ?>
        <p style="color:red;"><?= $error; ?></p>
      <?php endif; ?>

      <form method="post">
        <label>Username</label>
        <input type="text" name="username" required placeholder="Enter admin username">

        <label>Password</label>
        <input type="password" name="password" required placeholder="Enter password">

        <button type="submit" name="login" class="btn-auth">Login</button>
      </form>
    </div>
  </div>
</body>
</html>

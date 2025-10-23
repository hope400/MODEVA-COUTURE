  <?php include "components/user_header.php"?>

<?php
include 'components/connect.php';
session_start();


if (isset($_POST['login'])) {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $select = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' LIMIT 1");

  if (mysqli_num_rows($select) > 0) {
    $row = mysqli_fetch_assoc($select);

    // Verify the password using password_verify()
    if (password_verify($password, $row['password'])) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_name'] = $row['first_name'];
      header('location: home.php');
      exit;
    } else {
      $error[] = 'Incorrect password!';
    }
  } else {
    $error[] = 'Email not found!';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Modeva Couture</title>
  <link rel="stylesheet" href="css/user_style.css">
</head>
<body class="auth-body">

  <div class="auth-page">
    <div class="auth-box">
      <h2>Login Now</h2>

      <?php
      if (isset($error)) {
        foreach ($error as $err) {
          echo '<span class="error-msg">'.$err.'</span>';
        }
      }
      ?>

      <form method="post">
        <label>Your Email</label>
        <input type="email" name="email" required placeholder="johndoe@gmail.com">

        <label>Your Password</label>
        <input type="password" name="password" required placeholder="Enter Password">

        <button type="submit" name="login" class="btn-auth">Login Now</button>

        <p>Don’t have an account? <a href="register.php">Register Here</a></p>
      </form>
    </div>
  </div>

</body>
</html>


<?php
include 'components/connect.php';
session_start();

if (isset($_POST['register'])) {
  $fn = mysqli_real_escape_string($conn, $_POST['first_name']);
  $ln = mysqli_real_escape_string($conn, $_POST['last_name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
  if (mysqli_num_rows($check) > 0) {
    $error[] = 'Email already registered!';
  } else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insert = mysqli_query($conn, "INSERT INTO users (first_name, last_name, email, password) 
                VALUES ('$fn', '$ln', '$email', '$hashedPassword')");
    if ($insert) {
      header('Location: login.php');
      exit;
    } else {
      $error[] = 'Registration failed, please try again.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Modeva Couture</title>
  <link rel="stylesheet" href="css/user_style.css">
</head>
<body>

  <!-- Sticky header on top -->
  <?php include "components/user_header.php"?>

  <!-- Main wrapper to push content below header -->
  <main class="auth-page">
    <section class="auth-wrapper">
      <div class="auth-box">
        <h2>Create Account</h2>

        <?php
        if (isset($error)) {
          foreach ($error as $err) {
            echo '<span class="error-msg">'.$err.'</span>';
          }
        }
        ?>

        <form method="post">
          <label>First Name</label>
          <input type="text" name="first_name" required placeholder="Jane">

          <label>Last Name</label>
          <input type="text" name="last_name" required placeholder="Doe">

          <label>Email</label>
          <input type="email" name="email" required placeholder="janedoe@gmail.com">

          <label>Password</label>
          <input type="password" name="password" required placeholder="Create Password">

          <button type="submit" name="register" class="btn-auth">Register</button>

          <p>Already have an account? <a href="login.php">Login Here</a></p>
        </form>
      </div>
    </section>
  </main>


</body>
</html>

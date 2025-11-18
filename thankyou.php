<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You | Modeva Couture</title>

  <!-- ✅ Use your main site styles -->
  <link rel="stylesheet" href="css/user_style.css">
  <link rel="stylesheet" href="slick.css">
  <link rel="stylesheet" href="slick-theme.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      background: #fafafa;
      font-family: 'Poppins', sans-serif;
    }
    main.thankyou-container {
      max-width: 700px;
      margin: 160px auto 100px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,.08);
      padding: 50px 40px;
      text-align: center;
    }
    main.thankyou-container h1 {
      color: #333;
      font-size: 2rem;
      margin-bottom: 15px;
    }
    main.thankyou-container p {
      color: #555;
      margin-bottom: 25px;
      line-height: 1.5;
    }
    main.thankyou-container a.btn {
      display: inline-block;
      background: #f0b61f;
      color: #fff;
      text-decoration: none;
      padding: 12px 28px;
      border-radius: 6px;
      font-weight: 500;
      transition: background 0.3s ease;
    }
    main.thankyou-container a.btn:hover {
      background: #d39c10;
    }

    footer {
      margin-top: 60px;
    }
  </style>
</head>
<body>

  <!-- ✅ Include the same header component -->
  <?php include 'components/user_header.php'; ?>

  <main class="thankyou-container">
    <h1>Thank You for Your Order!</h1>
    <p>Your order has been placed successfully. A confirmation email will be sent to you shortly.</p>
    <a href="menu.php" class="btn"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
  </main>

  <!-- ✅ Include the same footer component -->
  <?php include 'components/user_footer.php'; ?>

  <!-- ✅ Include the same JS files your site uses -->
  <script src="js/jquary.js"></script>
  <script src="js/slick.js"></script>
  <script src="js/script.js"></script>
</body>
</html>

<?php

include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {

  $user_id = $_COOKIE['user_id'];
}else {
  //$user_id - '';
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- box icon cdn link -->
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel='stylesheet'>

  <!---------------------------bootstrap icon link------------------------------>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <!---------------------------bootstrap css link------------------------------>
  <!---------------------------slick slider link------------------------------>
  <link rel="stylesheet" type="text/css" href="slick.css" />
  <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
  
  <title>MODEVA Fashion Website Template</title>
</head>
<body>
  <?php 
  include 'components/user_header.php';

   ?>

   <?php 
  include 'components/user_footer.php';

   ?>


 <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link -->
    <script src="jquery.js"></script>
    <script src="slick.js"></script>

    <script type="text/javascript">
        <?php include 'script.js'; ?>
    </script>

    <!-- alert -->
    <?php include 'components/alert.php'; ?>




</body>
</html>
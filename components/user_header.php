
<header class="header">
  <div class="flex">
    <!-- Logo -->
    <a href="home.php" class="logo">
      <img src="image/logo (1).png">
    </a>

    <!-- Navbar -->
    <nav class="navbar">
      <a href="home.php">Home</a>
      <a href="about.php">About Us </a>
      <a href="order.php">Orders</a>
      <a href="menu.php">Shop</a>
      <a href="contact.php">Contact Us</a>

    </nav>

    <!-- Icons Section -->
    <div class="icons">
      <!-- Search Icon + Input -->
<div class="search-container">
  <i class="bx bx-search"></i>
  <div class="search-box">
    <form action="menu.php" method="get" onsubmit="return validateSearch()">
      <input type="text" name="q" id="searchInput" placeholder="Search products...">
    </form>
  </div>
</div>




      <!-- Profile Hover Box -->
<div class="profile-container">
  <i class="bx bx-user"></i>

  <div class="profile-login-box">
    <img src="image/man.png" alt="User Avatar">
    <p>Please login</p>
    <div class="profile-buttons">
      <a href="login.php" class="btn-login">Login</a>
      <a href="register.php" class="btn-register">Register</a>
    </div>
  </div>
</div>

      <a href="wishlist.php"><i class="bx bx-heart"></i></a>

      <!-- Cart -->
      <a href="cart.php" class="cart-icon"><i class="bx bx-shopping-bag"></i></a>
    </div>
  </div>
</header>

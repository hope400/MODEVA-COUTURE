<!-- admin_sidebar.php -->
<aside class="admin-sidebar">
  <div class="sidebar-header">
    <h2>Modeva Admin</h2>
  </div>

  <nav class="sidebar-nav">
    <ul>
      <li><a href="admin_dashboard.php">📊 Dashboard</a></li>
      <li><a href="admin_products.php">🛍 Manage Products</a></li>
      <li><a href="admin_add_product.php">➕ Add Product</a></li>
      <li><a href="admin_manage_orders.php">📦 Manage Orders</a></li>
      <li><a href="admin_discounts.php">💸 Manage Discounts</a></li>

      <li><a href="admin_manage_users.php">👤 Manage Users</a></li>
      <li><a href="admin_sales_report.php">📈 Sales Reports</a></li>
      <li><a href="admin_reviews.php"><i class="bx bx-comment-detail"></i> Reviews</a></li>



    </ul>
  </nav>

  <div class="sidebar-footer">
    <a href="../home.php" target="_blank" class="home-link">🏠 Go to Home Page</a>
    <a href="admin_login.php" class="logout-link">🚪 Logout</a>
  </div>
</aside>

<style>
  .admin-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 240px;
    height: 100%;
    background: #fff;
    box-shadow: 2px 0 10px rgba(0,0,0,0.08);
    padding: 20px 0;
    z-index: 1000;
  }

  .sidebar-header {
    text-align: center;
    margin-bottom: 30px;
  }

  .sidebar-header h2 {
    font-size: 1.3rem;
    color: #f0b61f;
  }

  .sidebar-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .sidebar-nav ul li {
    margin: 10px 0;
  }

  .sidebar-nav ul li a {
    display: block;
    padding: 10px 20px;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
  }

  .sidebar-nav ul li a:hover {
    background: #f0b61f;
    color: #fff;
    border-radius: 8px;
  }

  .sidebar-footer {
    position: absolute;
    bottom: 25px;
    left: 0;
    width: 100%;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .sidebar-footer a {
    background: #f0b61f;
    color: #fff;
    text-decoration: none;
    padding: 10px;
    margin: 0 20px;
    border-radius: 8px;
    font-weight: 500;
    transition: 0.3s;
  }

  .sidebar-footer a:hover {
    background: #d39c10;
  }

  /* Responsive collapse */
  @media (max-width: 768px) {
    .admin-sidebar {
      left: 0;
      top: 0;
      height: 100vh;
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }

    .admin-sidebar.active {
      transform: translateX(0);
    }
  }
</style>

<?php
include 'components/connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us | Modeva Couture</title>
  <link rel="stylesheet" href="css/user_style.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fafafa;
      color: #333;
    }
    main {
      max-width: 1100px;
      margin: 130px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      overflow: hidden;
    }
    .about-header {
      background: linear-gradient(90deg, #f0b61f, #d39c10);
      color: #fff;
      text-align: center;
      padding: 60px 20px;
    }
    .about-header h1 {
      font-size: 2rem;
      margin-bottom: 10px;
    }
    .about-header p {
      font-size: 1.1rem;
      opacity: 0.9;
    }
    .about-content {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      padding: 50px;
      align-items: center;
      justify-content: center;
    }
    .about-text {
      flex: 1 1 450px;
    }
    .about-text h2 {
      color: #f0b61f;
      font-size: 1.5rem;
      margin-bottom: 15px;
    }
    .about-text p {
      line-height: 1.7;
      margin-bottom: 15px;
    }
    .about-image {
      flex: 1 1 400px;
      text-align: center;
    }
    .about-image img {
      width: 100%;
      max-width: 420px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .values-section {
      background: #fff7e6;
      padding: 40px 50px;
      border-top: 1px solid #eee;
      text-align: center;
    }
    .values-section h2 {
      color: #d39c10;
      margin-bottom: 25px;
    }
    .values {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 30px;
    }
    .value-card {
      background: #fff;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .value-card i {
      font-size: 2rem;
      color: #f0b61f;
      margin-bottom: 10px;
    }
    .value-card h3 {
      font-size: 1.2rem;
      margin-bottom: 8px;
      color: #333;
    }
    .value-card p {
      color: #555;
      font-size: 0.95rem;
      line-height: 1.5;
    }
    @media(max-width: 768px) {
      .about-content {
        padding: 30px;
      }
    }
  </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<main>
  <section class="about-header">
    <h1>About Modeva Couture</h1>
    <p>Elegance redefined — crafted with passion, designed with purpose.</p>
  </section>

  <section class="about-content">
    <div class="about-text">
      <h2>Our Story</h2>
      <p>Founded with a vision to bring timeless elegance and modern design together, <strong>Modeva Couture</strong> has become a symbol of sophistication in the fashion world. From luxurious dresses to tailored suits, each piece is carefully crafted to empower confidence and celebrate individuality.</p>
      <p>Our team of designers and artisans work tirelessly to ensure that every collection reflects beauty, creativity, and quality craftsmanship. We don’t just make clothing — we create experiences that let you express your style effortlessly.</p>
    </div>

    <div class="about-image">
      <img src="image/about-banner.jpg" alt="Modeva Couture Boutique">
    </div>
  </section>

  <section class="values-section">
    <h2>Our Core Values</h2>
    <div class="values">
      <div class="value-card">
        <i class='bx bxs-heart'></i>
        <h3>Passion for Fashion</h3>
        <p>Every design is born out of a deep love for creativity and a desire to inspire confidence in every wearer.</p>
      </div>
      <div class="value-card">
        <i class='bx bxs-star'></i>
        <h3>Commitment to Quality</h3>
        <p>We believe luxury is in the details. Each piece is crafted with precision and premium materials that last.</p>
      </div>
      <div class="value-card">
        <i class='bx bx-group'></i>
        <h3>Customer Focus</h3>
        <p>Your satisfaction is at the heart of what we do. Our goal is to ensure every client feels valued and beautiful.</p>
      </div>
      <div class="value-card">
        <i class='bx bx-leaf'></i>
        <h3>Sustainability</h3>
        <p>We are dedicated to responsible production practices and promoting eco-conscious fashion choices.</p>
      </div>
    </div>
  </section>
</main>

<?php include 'components/user_footer.php'; ?>

</body>
</html>

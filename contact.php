<?php
include 'components/connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | Modeva Couture</title>
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
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .contact-header {
      text-align: center;
      padding: 60px 20px;
      background: linear-gradient(90deg, #f0b61f, #d39c10);
      color: #fff;
      border-radius: 12px;
      margin-bottom: 40px;
    }
    .contact-header h1 {
      font-size: 2rem;
      margin-bottom: 10px;
    }
    .contact-header p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    .contact-container {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
    }

    .contact-info, .contact-form {
      flex: 1 1 450px;
    }

    .contact-info h2, .contact-form h2 {
      font-size: 1.5rem;
      color: #f0b61f;
      margin-bottom: 20px;
    }

    .info-box {
      background: #fff7e6;
      padding: 20px;
      border-radius: 12px;
      border: 1px solid #f0b61f;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .info-box i {
      font-size: 2rem;
      color: #d39c10;
    }

    .info-text h3 {
      margin-bottom: 5px;
      font-size: 1.1rem;
      font-weight: 600;
    }

    .contact-form form {
      background: #fff7e6;
      padding: 25px;
      border-radius: 12px;
      border: 1px solid #f0b61f;
    }

    label {
      display: block;
      margin-top: 10px;
      font-weight: 500;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      margin-top: 5px;
    }

    textarea {
      resize: none;
      height: 100px;
    }

    .btn-send {
      margin-top: 20px;
      background: #f0b61f;
      color: #fff;
      border: none;
      padding: 12px 25px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
      transition: 0.3s;
      width: 100%;
    }

    .btn-send:hover {
      background: #d39c10;
    }

    .map {
      margin-top: 40px;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    iframe {
      width: 100%;
      height: 350px;
      border: none;
    }

    @media(max-width: 768px) {
      .contact-header h1 {
        font-size: 1.6rem;
      }
      .contact-header p {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<main>

  <!-- HEADER SECTION -->
  <div class="contact-header">
    <h1>Contact Modeva Couture</h1>
    <p>We’re here to help you with anything — style advice, order support, or general inquiries.</p>
  </div>

  <!-- CONTACT SECTION -->
  <div class="contact-container">

    <!-- LEFT: CONTACT INFO -->
    <div class="contact-info">
      <h2>Get in Touch</h2>

      <div class="info-box">
        <i class='bx bx-phone'></i>
        <div class="info-text">
          <h3>Phone</h3>
          <p>+1 (514) 555 5555</p>
        </div>
      </div>

      <div class="info-box">
        <i class='bx bx-envelope'></i>
        <div class="info-text">
          <h3>Email</h3>
          <p>modevacouture@gmail.com</p>
        </div>
      </div>

      <div class="info-box">
        <i class='bx bx-map'></i>
        <div class="info-text">
          <h3>Address</h3>
          <p>Montreal, Quebec, Canada</p>
        </div>
      </div>

      <div class="info-box">
        <i class='bx bx-time'></i>
        <div class="info-text">
          <h3>Business Hours</h3>
          <p>Mon – Sat: 9:00am – 6:00pm</p>
        </div>
      </div>
    </div>

    <!-- RIGHT: CONTACT FORM -->
    <div class="contact-form">
      <h2>Send Us a Message</h2>

      <form action="" method="POST" onsubmit="alert('📩 Message sent! We will get back to you soon.');">

        <label>Your Name</label>
        <input type="text" name="name" required>

        <label>Your Email</label>
        <input type="email" name="email" required>

        <label>Your Message</label>
        <textarea name="message" required></textarea>

        <button type="submit" class="btn-send">Send Message</button>
      </form>
    </div>

  </div>

  <!-- MAP SECTION -->
  <div class="map">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d22453.296916218587!2d-73.585!3d45.5017!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cc91a45f61a8e5d%3A0xe27a64875b0f518!2sMontreal%2C%20QC!5e0!3m2!1sen!2sca!4v1700000000000" 
      allowfullscreen="">
    </iframe>
  </div>

</main>

<?php include 'components/user_footer.php'; ?>

</body>
</html>

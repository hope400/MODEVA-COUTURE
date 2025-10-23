<?php
$host = 'localhost';
$user = 'root'; // default username for XAMPP
$pass = '';     // default password is blank
$dbname = 'modeva_db'; // make sure this matches your database name

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
  die("Database connection failed: " . mysqli_connect_error());
}

// Optional: uncomment for testing
// echo "Database connected successfully!";
?>


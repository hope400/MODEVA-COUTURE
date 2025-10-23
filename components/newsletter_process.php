<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Example: save to file (you can later connect to database instead)
        $file = fopen("../subscribers.txt", "a");
        fwrite($file, $email . "\n");
        fclose($file);

        echo "<script>alert('Thank you for subscribing!'); window.history.back();</script>";
    } else {
        echo "<script>alert('Please enter a valid email address.'); window.history.back();</script>";
    }
}
?>

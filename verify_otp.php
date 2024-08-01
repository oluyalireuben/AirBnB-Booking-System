<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli('localhost', 'root', '', 'airbnb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['verify_otp'])) {
    $email = $_SESSION['otp_email'];
    $otp = $_POST['otp'];

    $stmt = $conn->prepare("SELECT * FROM users_temp WHERE email = ? AND otp = ?");
    $stmt->bind_param("si", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Move the user from users_temp to users
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, user_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $row['username'], $row['password'], $row['email'], $row['user_type']);
        $stmt->execute();

        // Delete the user from users_temp
        $stmt = $conn->prepare("DELETE FROM users_temp WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo "Registration successful!";
    } else {
        echo "Invalid OTP.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Verify OTP</title>
</head>
<body>
<div class="container">
    <form action="verify_otp.php" method="POST">
        <h2>Verify OTP</h2>
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit" name="verify_otp">Verify</button>
    </form>
</div>
</body>
</html>

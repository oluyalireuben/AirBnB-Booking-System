<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli('localhost', 'root', '', 'airbnb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];
    $otp = rand(100000, 999999);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users_temp (username, password, email, user_type, otp) VALUES (?, ?, ?, ?, ?)");

    // Check if prepare() failed
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssi", $username, $password, $email, $user_type, $otp);

    if ($stmt->execute()) {
        // Send OTP to user email
        mail($email, "Your OTP Code", "Your OTP code is: $otp");

        $_SESSION['otp_email'] = $email;
        header('Location: verify_otp.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

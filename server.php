<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'airbnb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User registration logic
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_type = $_POST['user_type'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $user_type);
    $stmt->execute();
    $stmt->close();

    header('Location: login.html');
}

// User login logic
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        if ($user['user_type'] == 'admin') {
            header('Location: admin_dashboard.html');
        } else {
            header('Location: normal_dashboard.html');
        }
    } else {
        echo "Invalid credentials";
    }
}

// Room booking logic
if (isset($_POST['book'])) {
    $room = $_POST['room'];
    $days = $_POST['days'];
    $username = $_SESSION['user']['username'];

    $stmt = $conn->prepare("INSERT INTO bookings (username, room, days) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $username, $room, $days);
    $stmt->execute();
    $stmt->close();

    echo "Booking successful";
}

// Booking approval logic
if (isset($_POST['approve'])) {
    $booking_id = $_POST['booking_id'];

    $stmt = $conn->prepare("UPDATE bookings SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();

    header('Location: admin_dashboard.html');
}

// Booking cancellation logic
if (isset($_POST['cancel'])) {
    $booking_id = $_POST['booking_id'];

    $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();

    header('Location: admin_dashboard.html');
}

// Password reset logic
if (isset($_POST['reset_password'])) {
    $username = $_POST['username'];
    $new_password = password_hash('defaultpassword', PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_password, $username);
    $stmt->execute();
    $stmt->close();

    echo "Password reset to 'defaultpassword'. Please login and change your password.";
}

$conn->close();
?>

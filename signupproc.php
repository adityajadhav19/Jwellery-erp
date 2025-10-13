<?php
session_start();
include 'config.php'; // database connection

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];

// Force role = customer
$role = "customer";

// Hash password
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Check if username/email exists
$check = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
$check->bind_param("ss", $user, $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "❌ Username or Email already exists!";
} else {
    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        // Auto login after signup
        $_SESSION['username'] = $user;
        $_SESSION['role'] = $role;

        header("Location: user_dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
?>

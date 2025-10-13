<?php
// Database connection settings
$host = "localhost";       // usually "localhost"
$user = "root";            // your MySQL username
$pass = "";                // your MySQL password (default is empty in XAMPP)
$dbname = "jewellery_erp"; // your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Set charset to utf8mb4 for full Unicode support
$conn->set_charset("utf8mb4");

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>

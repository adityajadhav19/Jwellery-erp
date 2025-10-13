<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit;
}
include("config.php"); // your connection file

$employee_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action === "attendance") {
        $status = $_POST['status'];
        $date = date("Y-m-d");
        $stmt = $conn->prepare("INSERT INTO attendance (employee_id, date, status) VALUES (?,?,?) 
                                ON DUPLICATE KEY UPDATE status=?");
        $stmt->bind_param("isss", $employee_id, $date, $status, $status);
        $stmt->execute();
        header("Location: employee_dashboard.php");
        exit;
    }

    if ($action === "leave") {
        $start = $_POST['start_date'];
        $end = $_POST['end_date'];
        $reason = $_POST['reason'];
        $stmt = $conn->prepare("INSERT INTO leave_requests (employee_id, start_date, end_date, reason, status) VALUES (?,?,?,?, 'Pending')");
        $stmt->bind_param("isss", $employee_id, $start, $end, $reason);
        $stmt->execute();
        header("Location: employee_dashboard.php");
        exit;
    }

    if ($action === "inventory_request") {
        $item = $_POST['item_name'];
        $qty = $_POST['quantity'];
        $reason = $_POST['reason'];
        $stmt = $conn->prepare("INSERT INTO inventory_requests (employee_id, item_name, quantity, reason, status) VALUES (?,?,?,?, 'Pending')");
        $stmt->bind_param("isis", $employee_id, $item, $qty, $reason);
        $stmt->execute();
        header("Location: employee_dashboard.php");
        exit;
    }
}
?>

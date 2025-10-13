<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit;
}
include("config.php"); // your connection file

$employee_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee Dashboard</title>
  <link rel="stylesheet" href="assets/css/employee.css">
  
</head>
<body>
    <!-- Navbar -->
  <header>
    <div class="logo">💍 Indira Jewellers</div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="index.php">Logout</a></li>
      </ul>
    </nav>
  </header>
  <center>
  <h2>Welcome, <?php echo $_SESSION['username']; ?> (Employee)</h2>
  </center>
  <div class="container">

    <h2>📦 Inventory (View Only)</h2>
    <?php
    $res = $conn->query("SELECT * FROM inventory");
    echo "<table><tr><th>ID</th><th>Name</th><th>Type</th><th>Quantity</th><th>Price</th></tr>";
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['type']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['price']}</td>
              </tr>";
    }
    echo "</table>";
    ?>

    <h2>🕒 Mark Attendance</h2>
    <form action="employeeproc.php" method="post">
      <input type="hidden" name="action" value="attendance">
      <select name="status" required>
        <option value="">-- Select Status --</option>
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
        <option value="Late">Late</option>
        <option value="Leave">Leave</option>
      </select>
      <button type="submit">Submit</button>
    </form>

    <h2>📝 Leave Request</h2>
    <form action="employeeproc.php" method="post">
      <input type="hidden" name="action" value="leave">
      <label>Start Date:</label><input type="date" name="start_date" required>
      <label>End Date:</label><input type="date" name="end_date" required>
      <textarea name="reason" placeholder="Reason" required></textarea>
      <button type="submit">Request Leave</button>
    </form>

    <h2>📨 Inventory Request</h2>
    <form action="employeeproc.php" method="post">
      <input type="hidden" name="action" value="inventory_request">
      <label>Item Name:</label><input type="text" name="item_name" required>
      <label>Quantity Needed:</label><input type="number" name="quantity" min="1" required>
      <textarea name="reason" placeholder="Reason for request"></textarea>
      <button type="submit">Submit Request</button>
    </form>

    <h2>📊 My History</h2>
    <h3>Attendance</h3>
    <?php
    $att = $conn->query("SELECT * FROM attendance WHERE employee_id='$employee_id' ORDER BY date DESC");
    echo "<table><tr><th>Date</th><th>Status</th></tr>";
    while ($row = $att->fetch_assoc()) {
        echo "<tr><td>{$row['date']}</td><td>{$row['status']}</td></tr>";
    }
    echo "</table>";
    ?>

    <h3>Leave Requests</h3>
    <?php
    $leave = $conn->query("SELECT * FROM leave_requests WHERE employee_id='$employee_id' ORDER BY start_date DESC");
    echo "<table><tr><th>Start</th><th>End</th><th>Reason</th><th>Status</th></tr>";
    while ($row = $leave->fetch_assoc()) {
        echo "<tr><td>{$row['start_date']}</td><td>{$row['end_date']}</td><td>{$row['reason']}</td><td>{$row['status']}</td></tr>";
    }
    echo "</table>";
    ?>

    <h3>Inventory Requests</h3>
    <?php
    $req = $conn->query("SELECT * FROM inventory_requests WHERE employee_id='$employee_id' ORDER BY created_at DESC");
    echo "<table><tr><th>Item</th><th>Qty</th><th>Reason</th><th>Status</th></tr>";
    while ($row = $req->fetch_assoc()) {
        echo "<tr><td>{$row['item_name']}</td><td>{$row['quantity']}</td><td>{$row['reason']}</td><td>{$row['status']}</td></tr>";
    }
    echo "</table>";
    ?>

  </div>
</body>
</html>

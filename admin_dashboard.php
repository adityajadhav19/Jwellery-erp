<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access denied!");
}

include 'config.php'; // database connection
// Fetch all employees
$employees = $conn->query("SELECT * FROM users WHERE role='employee'");
?>

<link rel="stylesheet" href="assets/css/admin.css">

<!-- Navbar -->
<header>
    <div class="logo">💍 Indira Jewellers</div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<center>
    <h2>Welcome, <?php echo $_SESSION['username']; ?> (Admin)</h2>
</center>

<script>
    function openTab(tabName) {
        // Hide all tab contents
        let contents = document.querySelectorAll(".tab-content");
        contents.forEach(c => c.classList.remove("active"));

        // Remove 'active' from all buttons
        let buttons = document.querySelectorAll(".tab-buttons button");
        buttons.forEach(b => b.classList.remove("active"));

        // Show the clicked tab and activate its button
        document.getElementById(tabName).classList.add("active");
        document.querySelector("[data-tab='" + tabName + "']").classList.add("active");
    }
</script>

<div class="tab-buttons">
    <button data-tab="employees" onclick="openTab('employees')" class="active">Employees</button>
    <button data-tab="inventory" onclick="openTab('inventory')">Inventory</button>
    <button data-tab="analytics" onclick="openTab('analytics')">Employee Analytics</button>
</div>

<!-- Employees Tab -->
<div id="employees" class="tab-content active">
    <center>
        <h3>Add New Employee</h3>
        <form action="adminproc.php" method="POST">
            <input type="hidden" name="action" value="add">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <input type="submit" value="Add Employee">
        </form>
    </center>

    <h3>All Employees</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($emp = $employees->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $emp['id']; ?></td>
                <td><?php echo $emp['username']; ?></td>
                <td><?php echo $emp['email']; ?></td>
                <td>
                    <form action="adminproc.php" method="POST" style="display:inline">
                        <input type="hidden" name="action" value="edit_form">
                        <input type="hidden" name="id" value="<?php echo $emp['id']; ?>">
                        <input type="submit" value="Edit">
                    </form>
                    <form action="adminproc.php" method="POST" style="display:inline"
                        onsubmit="return confirm('Are you sure?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $emp['id']; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<!-- Inventory Tab -->
<div id="inventory" class="tab-content">
    <center>
        <h3>Add New Jewelry Item</h3>
        <form action="adminproc.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_item">

            <label>Name:</label><br>
            <input type="text" name="name" required><br><br>

            <label>Type:</label><br>
            <input type="text" name="type" required><br><br>

            <label>Quantity:</label><br>
            <input type="number" name="quantity" required><br><br>

            <label>Price:</label><br>
            <input type="number" step="0.01" name="price" required><br><br>

            <label>Description:</label><br>
            <textarea name="description"></textarea><br><br>

            <label>Image:</label><br>
            <input type="file" name="image" accept="image/*"><br><br>

            <input type="submit" value="Add Item">
        </form>

        <h3>Inventory List</h3>
        <table border="1" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php
            $items = $conn->query("SELECT * FROM inventory");
            while ($item = $items->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['type']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['price']; ?></td>
                    <td><?php echo $item['description']; ?></td>
                    <td>
                        <form action="adminproc.php" method="POST" style="display:inline">
                            <input type="hidden" name="action" value="edit_item_form">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <input type="submit" value="Edit">
                        </form>
                        <form action="adminproc.php" method="POST" style="display:inline"
                            onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="action" value="delete_item">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </center>
</div>

<!-- Analytics Tab -->
<div id="analytics" class="tab-content">
    <h2>📊 Employee Analytics</h2>

    <div class="analytics-section">
        <h3>Attendance Overview</h3>
        <p>Show attendance % / status here (from `attendance` table).</p>
    </div>

    <div class="analytics-section">
        <h3>Leave Requests</h3>
        <table border="1" cellpadding="5">
            <tr>
                <th>Employee</th>
                <th>From</th>
                <th>To</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT lr.*, u.username 
                FROM leave_requests lr 
                JOIN users u ON lr.employee_id = u.id
                ORDER BY lr.created_at DESC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>{$row['username']}</td>
                    <td>{$row['start_date']}</td>
                    <td>{$row['end_date']}</td>
                    <td>{$row['reason']}</td>
                    <td>{$row['status']}</td>
                    <td>";

                    // Only show approve/reject buttons if status is Pending
                    if ($row['status'] == 'Pending') {
                        echo "<form action='adminproc.php' method='POST' style='display:inline'>
                            <input type='hidden' name='leave_id' value='{$row['id']}'>
                            <input type='submit' name='approve_leave' value='Approve'>
                          </form>
                          <form action='adminproc.php' method='POST' style='display:inline'>
                            <input type='hidden' name='leave_id' value='{$row['id']}'>
                            <input type='submit' name='reject_leave' value='Reject'>
                          </form>";
                    } else {
                        echo "-";
                    }

                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No leave requests</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="analytics-section">
        <h3>Inventory Requests</h3>
        <p>Similar table for employee inventory requests.</p>
    </div>
</div>
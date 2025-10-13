<?php
session_start();

// Only admin access
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    die("Access denied!");
}

include 'config.php'; // database connection
$action = $_POST['action'] ?? '';

// ------------------------- Employee Management -------------------------
if($action == 'add'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'employee';

    // Check for duplicates
    $check = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
        echo "Username or email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        if($stmt->execute()){
            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
elseif($action == 'delete'){
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='employee'");
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
elseif($action == 'edit_form'){
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=? AND role='employee'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    ?>

    <h2>Edit Employee</h2>
    <form action="adminproc.php" method="POST">
        <input type="hidden" name="action" value="edit_save">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($result['id']); ?>">

        <label>Username:</label><br>
        <input type="text" name="username" value="<?php echo htmlspecialchars($result['username']); ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($result['email']); ?>" required><br><br>

        <label>Password: (Leave blank to keep current)</label><br>
        <input type="password" name="password"><br><br>

        <input type="submit" value="Save Changes">
    </form>

    <?php
}
elseif($action == 'edit_save'){
    $id = intval($_POST['id']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    if(!empty($_POST['password'])){
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=? WHERE id=? AND role='employee'");
        $stmt->bind_param("sssi", $username, $email, $password, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, email=? WHERE id=? AND role='employee'");
        $stmt->bind_param("ssi", $username, $email, $id);
    }

    if($stmt->execute()){
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// ------------------------- Inventory Management -------------------------
if ($action == "add_item") {
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);

    // Handle image upload
    $imagePath = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // create folder if not exists
        }
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $stmt = $conn->prepare("INSERT INTO inventory (name, type, quantity, price, description, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdss", $name, $type, $quantity, $price, $description, $imagePath);
    if($stmt->execute()){
        header("Location: admin_dashboard.php"); 
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// ------------------------- Leave Requests -------------------------
if (isset($_POST['approve_leave'])) {
    $id = intval($_POST['leave_id']);
    $stmt = $conn->prepare("UPDATE leave_requests SET status=? WHERE id=?");
    $status = "Approved";
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}

if (isset($_POST['reject_leave'])) {
    $id = intval($_POST['leave_id']);
    $stmt = $conn->prepare("UPDATE leave_requests SET status=? WHERE id=?");
    $status = "Rejected";
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}

$conn->close();
?>

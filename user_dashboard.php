<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'customer'){
    die("Access denied!");
}

include 'config.php';

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] += $quantity; // increase quantity
    } else {
        $_SESSION['cart'][$item_id] = $quantity; // new item
    }
}

// Handle Place Order (just clears cart for now)
if (isset($_POST['place_order'])) {
    echo "<p style='color:green;'>✅ Order placed successfully!</p>";
    $_SESSION['cart'] = []; // clear cart
}

// Fetch inventory
$items = $conn->query("SELECT * FROM inventory");
// Handle Place Order
if (isset($_POST['place_order'])) {
    if (!empty($_SESSION['cart'])) {
        $user = $_SESSION['username'];
        $res = $conn->query("SELECT id FROM users WHERE username='$user'");
        $userRow = $res->fetch_assoc();
        $user_id = $userRow['id'];

        $total = 0;
        foreach ($_SESSION['cart'] as $id => $qty) {
            $q = $conn->query("SELECT price FROM inventory WHERE id=$id");
            $row = $q->fetch_assoc();
            $total += $row['price'] * $qty;
        }

        // insert into orders
        $conn->query("INSERT INTO orders (user_id, total) VALUES ($user_id, $total)");
        $order_id = $conn->insert_id;

        // insert order items
        foreach ($_SESSION['cart'] as $id => $qty) {
            $q = $conn->query("SELECT price FROM inventory WHERE id=$id");
            $row = $q->fetch_assoc();
            $price = $row['price'];
            $conn->query("INSERT INTO order_items (order_id, item_id, quantity, price) 
                          VALUES ($order_id, $id, $qty, $price)");
        }

        // clear cart and redirect to invoice
        $_SESSION['cart'] = [];
        header("Location: invoice.php?order_id=" . $order_id);
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="assets/css/user.css">
</head>
<body>
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
    <h2>Welcome, <?php echo $_SESSION['username']; ?> (User)</h2></center>
    

    <h3>Available Jewelry</h3>
    <table>
        <tr>
            <th>Image</th><th>Name</th><th>Type</th><th>Price</th><th>Quantity</th><th>Action</th>
        </tr>
        <?php while($item = $items->fetch_assoc()){ ?>
        <tr>
            <td>
                <?php if (!empty($item['image'])) { ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>" width="80" height="80" style="object-fit:cover;">
                <?php } else { ?>
                    <span>No Image</span>
                <?php } ?>

            </td>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['type']; ?></td>
            <td><?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $item['quantity']; ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h3>🛒 Your Cart</h3>
    <?php if (!empty($_SESSION['cart'])) { ?>
        <form method="POST">
        <table>
            <tr><th>Image</th><th>Item</th><th>Quantity</th></tr>
            <?php
            foreach ($_SESSION['cart'] as $id => $qty) {
                $q = $conn->query("SELECT name, image FROM inventory WHERE id=$id");
                $row = $q->fetch_assoc();
                echo "<tr>
                        <td>".(!empty($row['image']) ? "<img src='uploads/".$row['image']."' width='50' height='50'>" : "No Image")."</td>
                        <td>".$row['name']."</td>
                        <td>".$qty."</td>
                      </tr>";
            }
            ?>
        </table>
        <button type="submit" name="place_order">Place Order</button>
        </form>
    <?php } else { ?>
        <p>Your cart is empty</p>
    <?php } ?>
</body>
</html>

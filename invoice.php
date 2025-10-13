<?php
session_start();
include 'config.php';

if (!isset($_GET['order_id'])) {
    die("No order found!");
}

$order_id = intval($_GET['order_id']);

// fetch order
$order = $conn->query("SELECT * FROM orders WHERE id=$order_id")->fetch_assoc();
$user = $conn->query("SELECT username FROM users WHERE id=".$order['user_id'])->fetch_assoc();
$items = $conn->query("SELECT oi.quantity, oi.price, i.name, i.image 
                       FROM order_items oi 
                       JOIN inventory i ON oi.item_id = i.id 
                       WHERE oi.order_id=$order_id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?php echo $order_id; ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #f4f4f4; }
        .total { text-align: right; font-weight: bold; padding: 15px; }
    </style>
</head>
<body>
    <h2>🧾 Invoice - Order #<?php echo $order_id; ?></h2>
    <p><b>User:</b> <?php echo $user['username']; ?></p>
    <p><b>Date:</b> <?php echo $order['order_date']; ?></p>

    <table>
        <tr><th>Image</th><th>Item</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>
        <?php 
        $grandTotal = 0;
        while ($row = $items->fetch_assoc()) { 
            $sub = $row['price'] * $row['quantity'];
            $grandTotal += $sub;
        ?>
        <tr>
            <?php if (!empty($item['image'])) { ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>" width="80" height="80" style="object-fit:cover;">
            <?php } else { ?>
                <span>No Image</span>
            <?php } ?>
            <td><?php echo $row['name']; ?></td>
            <td>₹<?php echo $row['price']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td>₹<?php echo $sub; ?></td>
        </tr>
        <?php } ?>
    </table>

    <p class="total">Grand Total: ₹<?php echo $grandTotal; ?></p>
</body>
</html>

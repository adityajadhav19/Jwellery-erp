<?php
// Connect to the database
include 'config.php';

// Fetch products
$sql = "SELECT * FROM inventory ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Products</title>
    <link rel="stylesheet" href="assets/css/products.css">
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
        <li><a href="login.php">login</a></li>
      </ul>
    </nav>
  </header>
    <center>
        <h1>Our Jewelry Collection</h1>
         <p class="subtitle">Handcrafted elegance for every occasion</p>
    </center>
    <div class="products-container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<img src="uploads/'.$row['image'].'" alt="'.$row['name'].'">';
                echo '<h2>'.$row['name'].'</h2>';
                echo '<p>Type: '.$row['type'].'</p>';
                echo '<p>Quantity: '.$row['quantity'].'</p>';
                echo '<p class="price">₹'.$row['price'].'</p>';
                echo '<p class="description">'.$row['description'].'</p>';
                echo '</div>';
            }
        } else {
            echo '<p class="no-products">No products found.</p>';
        }
        ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>

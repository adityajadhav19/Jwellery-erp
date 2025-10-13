<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Elegant Jewelry</title>
    <link rel="stylesheet" href="assets/css/contact.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
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
        <li><a href="login.php">login</a></li>
      </ul>
    </nav>
  </header>
    <center>
        <h1>Contact Us</h1>
        <p class="subtitle">We would love to hear from you</p>
    </header>
    </center>
    <div class="contact-container">
        <form action="contact-submit.php" method="POST">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" required></textarea>

            <button type="submit">Send Message</button>
        </form>

        <div class="contact-info">
            <h3>Our Address</h3>
            <p>123 Jewelry Street, Your City, India</p>
            <h3>Email</h3>
            <p>info@elegantjewelry.com</p>
            <h3>Phone</h3>
            <p>+91 98765 xxxxx</p>
        </div>
    </div>
</body>
</html>

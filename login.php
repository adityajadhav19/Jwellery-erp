<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jewellery ERP - Login</title>
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body class="login-body">

  <div class="login-container">
    <div class="login-box">
      <h2>💍 Jewellery ERP</h2>
      <p class="subtitle">Sign in to continue</p>

      <!-- Login Form -->
      <form action="loginproc.php" method="POST">
        <div class="form-group">
          <input type="text" name="username" placeholder="Enter Username" required>
        </div>
        <div class="form-group">
          <input type="password" name="password" placeholder="Enter Password" required>
        </div>
        <button type="submit" class="btn">Login</button>
      </form>
      <div class="error-popup" id="errorBox"><?= $error ?? "" ?></div>

<script>
let errorBox = document.getElementById("errorBox");
if (errorBox.innerText.trim() !== "") {
    errorBox.style.display = "block";
}
</script>
      <!-- Extra Links -->
      <div class="extra-links">
        <a href="signup.php">Create Account</a>
      </div>
    </div>
  </div>

</body>
</html>

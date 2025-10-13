<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jewellery ERP - Signup</title>
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body class="login-body">

  <div class="login-container">
    <div class="login-box">
      <h2>📝 Create Account</h2>
      <p class="subtitle">Register to access ERP</p>

      <!-- Signup Form -->
      <form action="signupproc.php" method="POST">
        <div class="form-group">
          <input type="text" name="username" placeholder="Choose Username" required>
        </div>
        <div class="form-group">
          <input type="email" name="email" placeholder="Enter Email" required>
        </div>
        <div class="form-group">
          <input type="password" name="password" placeholder="Enter Password" required>
        </div>
        <button type="submit" class="btn">Signup</button>
      </form>

      <!-- Back to Login -->
      <div class="extra-links">
        <a href="login.php">Already have an account? Login</a>
      </div>
    </div>
  </div>

</body>
</html>

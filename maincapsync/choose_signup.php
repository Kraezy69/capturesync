<!-- choose_signup.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Choose Role - CaptureSync</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <header class="main-header">
    <a href="landing.php" class="logo-link">
      <img src="logo/C__7_-removebg-preview.png" alt="CaptureSync Logo">
      <span>CAPTURESYNC</span>
    </a>
    <div class="header-right">
      <button class="go-back-btn" onclick="history.back()">Go Back</button>
    </div>
  </header>
  <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh;">
    <h2 style="font-size: 2rem; margin-bottom: 40px; color: #2563eb;">Join as a Client or Photographer</h2>
    <form action="" method="POST" style="width: 100%; max-width: 500px;">
      <div class="role-options" style="display: flex; justify-content: center; gap: 40px; margin-bottom: 30px;">
        <div class="role-option" style="background-color: #f5f7fb; color: #222; padding: 20px; width: 220px; border-radius: 14px; cursor: pointer; transition: transform 0.2s; position: relative; box-shadow: 0 2px 10px rgba(0,0,0,0.07);">
          <input type="radio" name="role" value="client" id="client" checked style="position: absolute; top: 15px; right: 15px;">
          <label for="client" style="display: block; font-size: 16px; margin-top: 20px;">I'm a Client, looking for Photographer</label>
        </div>
        <div class="role-option" style="background-color: #f5f7fb; color: #222; padding: 20px; width: 220px; border-radius: 14px; cursor: pointer; transition: transform 0.2s; position: relative; box-shadow: 0 2px 10px rgba(0,0,0,0.07);">
          <input type="radio" name="role" value="photographer" id="photographer" style="position: absolute; top: 15px; right: 15px;">
          <label for="photographer" style="display: block; font-size: 16px; margin-top: 20px;">I'm a Photographer, looking for clients</label>
        </div>
      </div>
      <button type="submit" class="btn">Continue</button>
    </form>
    <div class="links" style="margin-top: 20px;">
      Already have an account? <a href="main.php">Log in</a>
    </div>
  </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $role = $_POST['role'];

  if ($role === "client") {
    header("Location: signup.php"); // ✅ Direct to signup.php for clients
  } else if ($role === "photographer") {
    header("Location: signup_photographer.php");
  }
  exit();
}
?>

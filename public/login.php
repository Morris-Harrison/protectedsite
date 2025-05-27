<?php
session_start();
require_once '../config/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        if (!$user['is_verified']) {
            $error = "Please verify your email before logging in.";
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION["authenticated"] = true;
            $_SESSION["user_email"] = $email;
            header("Location: protected.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
  <h2>Login</h2>
  <form method="POST">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
  </form>
  <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>

<?php
require_once '../config/db.php';

$message = '';

if (isset($_GET['registered'])) {
    $message = "Registration successful! Please log in.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!$username || !$password) {
        $message = "Please fill in all fields.";
    } else {
        // Look for user in DB
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Start session and save user info
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['authenticated'] = true; // <-- Add this line

            // Redirect to protected page
            header("Location: protected.php");
            exit;
        } else {
            $message = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>
<form method="post" action="login.php">
    <label>Username:<br><input type="text" name="username" required></label><br>
    <label>Password:<br><input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>

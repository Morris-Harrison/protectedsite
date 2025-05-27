<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: protected.php");
    exit();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: protected.php");
        exit();
    } else {
        $error = "Incorrect email or password.";
    }
}
?>

<form method="POST">
    <h2>Login</h2>
    <input name="email" type="email" placeholder="Email" required><br>
    <input name="password" type="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<p><a href="register.php">Register here</a></p>
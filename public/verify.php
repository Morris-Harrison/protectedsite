<?php
require_once '../config/db.php';

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND verify_token = ?");
    $stmt->execute([$email, $token]);
    $user = $stmt->fetch();

    if ($user) {
        $update = $pdo->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE email = ?");
        $update->execute([$email]);
        echo "<h2>Email verified successfully! You can now <a href='login.php'>log in</a>.</h2>";
    } else {
        echo "<h2>Invalid verification link or already verified.</h2>";
    }
} else {
    echo "<h2>Missing email or token.</h2>";
}
?>

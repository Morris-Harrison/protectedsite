<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
<p>This is a protected page.</p>
<a href="logout.php">Logout</a>
<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Protected Page</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Welcome to the protected content!</h1>
  <p>You have successfully logged in.</p>
  <a href="logout.php">Logout</a>
</body>
</html>

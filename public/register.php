<?php
require_once '../config/db.php';
require '../vendor/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/src/SMTP.php';
require '../vendor/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(16)); // Unique token

    // Insert into DB
    $stmt = $pdo->prepare("INSERT INTO users (email, username, password_hash, verify_token) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$email, $username, $password, $token]);

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mariomorris@outlook.com'; // your Outlook email
            $mail->Password = 'nqyj ksaj squd ozbj';              // your Outlook app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your_outlook_email@outlook.com', 'Protected Site');
            $mail->addAddress($email, $username);

            // Compose verification email
            $verifyUrl = "http://localhost/protectedsite/public/verify.php?email=$email&token=$token";
            $mail->isHTML(true);
            $mail->Subject = 'Verify your account';
            $mail->Body    = "Click the link to verify your account: <a href='$verifyUrl'>$verifyUrl</a>";

            $mail->send();
            $success = "Registration successful! Please check your email to verify your account.";
        } catch (Exception $e) {
            $error = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
</head>
<body>
  <h2>Register</h2>
  <form method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Register</button>
  </form>
  <?php
  if ($error) echo "<p style='color:red;'>$error</p>";
  if ($success) echo "<p style='color:green;'>$success</p>";
  ?>
</body>
</html>

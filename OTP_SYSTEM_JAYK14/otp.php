<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
// Create a new PHPMailer instance
$Correo = new PHPMailer(true);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAYK14 email OTP</title>
</head>
<body>
    <form action="sendotp.php" method="post">
    <input type="email" name="email">
    <button name="send">Send</button>
    </form>

</body>
</html>

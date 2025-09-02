<?php
include 'connect.php'; // Ensure $connect is your mysqli connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer autoloader

if (isset($_POST['send'])) {
    // Start session
    session_start();

    // Sanitize and fetch email from POST
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Generate OTP
    $otp = rand(100000, 999999);

    // Save OTP to database using prepared statement
    $stmt = $connect->prepare("INSERT INTO otp (email, otp) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();

    // Store email in session for verification later
    $_SESSION['email'] = $email;

    // Send Email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "// your Gmail"; // your Gmail
        $mail->Password = ""; // your app password
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        $mail->setFrom("// your Gmail", "JAYK14");
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Your OTP Code for Two-Step Verification";

        // Email Body with embedded OTP
        $mail->Body = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <title>2FA Email</title>
            <style>
                body { background-color: #f5f5f5; padding: 20px; font-family: Arial, sans-serif; text-align: center; }
                .email-container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
                .code-box { background: #e0f2ff; padding: 12px 24px; font-size: 1.5rem; font-weight: bold; color: #1e40af; border-radius: 6px; display: inline-block; margin: 20px 0; }
                .footer { color: #888; font-size: 12px; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="email-container">
                <h2>🔐 Two-Factor Authentication</h2>
                <p>Hello,</p>
                <p>Your OTP code for verifying your account is:</p>
                <div class="code-box">' . $otp . '</div>
                <p>This code will expire in 10 minutes. If you did not request this, please ignore this email.</p>
                <div class="footer">— JAY K14 Security Team</div>
            </div>
        </body>
        </html>';

        // Send the email
        $mail->send();

        // Redirect to verification page
        header("Location: verify.php");
        exit();
    } catch (Exception $e) {
        echo "Error sending OTP: {$mail->ErrorInfo}";
    }
}
?>

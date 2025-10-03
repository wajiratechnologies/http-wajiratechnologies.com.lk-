<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email   = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST["subject"] ?? ''));
    $type    = htmlspecialchars(trim($_POST["type"] ?? ''));
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($name) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "Please fill in all required fields correctly."]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP SETTINGS (Use your SMTP provider or Gmail SMTP)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // e.g., smtp.gmail.com
        $mail->SMTPAuth   = true;
        $mail->Username   = 'wajiratechnologies@gmail.com'; // Your email
        $mail->Password   = 'pcaa pkpv necr sfbe';   // App password or real password
        $mail->SMTPSecure = 'tls';            // or 'ssl'
        $mail->Port       = 587;              // 587 for TLS, 465 for SSL

        // Email content
        $mail->setFrom($email, $name);
        $mail->addAddress('wajiratechnologies@gmail.com'); // Who gets the email
        $mail->Subject = $subject ?: 'New Contact Message';

        $body = "
            <strong>Name:</strong> $name<br>
            <strong>Email:</strong> $email<br>
            <strong>Service Type:</strong> $type<br>
            <strong>Message:</strong><br>$message
        ";

        $mail->isHTML(true);
        $mail->Body = $body;

        $mail->send();
        echo json_encode(["status" => "success", "message" => "Your message has been sent successfully!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

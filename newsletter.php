<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST["email1"]), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message1" => "Please enter a valid email address."]);
        exit;
    }

    // Save to file
    $file = 'subscribers.txt';
    file_put_contents($file, $email . PHP_EOL, FILE_APPEND);

    echo json_encode(["status" => "success", "message1" => "Thank you for subscribing!"]);
    exit;
}

echo json_encode(["status" => "error", "message1" => "Invalid request."]);

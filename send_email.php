<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Set correct timezone
date_default_timezone_set('Asia/Kolkata');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function sendAppointmentEmail($email, $name, $age, $gender, $date, $time, $address) {
    $mail = new PHPMailer(true);

    // Ensure date is properly parsed
    $timestamp = strtotime($date);
    $formattedDate = $timestamp ? date('l, F j, Y', $timestamp) : htmlspecialchars($date);
    $formattedAddress = nl2br(htmlspecialchars($address));

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sivakumarb3928@gmail.com';
        $mail->Password = 'sqwy eleh iunc cjsu';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('sivakumarb3928@gmail.com', 'Doctor Appointment System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Appointment Confirmation for $name";
        $mail->Body = "
        <h2>Appointment Confirmation</h2>
        <p>Dear $name,</p>
        <p>Thank you for booking your appointment. Here are your details:</p>
        <p><strong>Date:</strong> $formattedDate</p>
        <p><strong>Time:</strong> $time</p>
        <p><strong>Address:</strong><br>$formattedAddress</p>
        <p><strong>Notes:</strong></p>
        <ul>
            <li>Arrive 10 mins early</li>
            <li>Bring ID and medical docs</li>
            <li>Fasting may be needed for tests</li>
        </ul>";

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Email sent successfully']);
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        echo json_encode(['success' => false, 'message' => 'Failed to send email']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $email = $data['email'] ?? '';
    $name = $data['name'] ?? '';
    $age = $data['age'] ?? '';
    $gender = $data['gender'] ?? '';
    $date = $data['date'] ?? '';
    $time = $data['time'] ?? '';
    $address = $data['address'] ?? '';

    sendAppointmentEmail($email, $name, $age, $gender, $date, $time, $address);
}
?>

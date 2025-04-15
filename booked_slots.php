<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require 'config.php';

$date = $_GET['date'] ?? null;

if (!$date) {
    http_response_code(400);
    echo json_encode(["message" => "Date required"]);
    exit;
}

$sql = "SELECT time FROM appointments WHERE date = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$date]);
$slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode(["bookedSlots" => $slots]);
?>

<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CORS headers - must be at the top!
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

$doorNo = $data['doorNo'] ?? '';
$street = $data['street'] ?? '';
$landmark = $data['landmark'] ?? '';
$area = $data['area'] ?? '';
$city = $data['city'] ?? '';

if (!$doorNo || !$street || !$area || !$city) {
    echo json_encode(["success" => false, "message" => "Required fields are missing"]);
    exit;
}

$sql = "INSERT INTO addresses (doorNo, street, landmark, area, city) VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$doorNo, $street, $landmark, $area, $city])) {
    echo json_encode(["success" => true, "message" => "Address saved successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to save address"]);
}
?>

<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST");

$host = "localhost";
$user = "root";
$password = "";
$database = "doctor_appointment";

// Connect to MySQL
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "available" => false,
        "message" => "Database connection failed"
    ]);
    exit;
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

$date = $data['date'] ?? null;
$time = $data['time'] ?? null;

if (!$date || !$time) {
    http_response_code(400);
    echo json_encode([
        "available" => false,
        "message" => "Date and time are required"
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM appointments WHERE date = ? AND time = ?");
$stmt->bind_param("ss", $date, $time);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    echo json_encode([
        "available" => false,
        "message" => "Slot already booked"
    ]);
} else {
    echo json_encode([
        "available" => true
    ]);
}

$stmt->close();
$conn->close();
?>

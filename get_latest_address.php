<?php
header("Access-Control-Allow-Origin: *"); // Allow from any origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require 'config.php';

$sql = "SELECT * FROM addresses WHERE id = (SELECT MAX(id) FROM addresses)";
try {
    $stmt = $pdo->query($sql);
    $result = $stmt->fetch();
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(["message" => "No address found"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error fetching address"]);
}
?>

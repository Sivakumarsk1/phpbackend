    <?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    require 'config.php';
    require 'send_email.php';

    $data = json_decode(file_get_contents("php://input"), true);

    $email = $data['email'] ?? null;
    $name = $data['name'] ?? null;
    $age = $data['age'] ?? null;
    $gender = $data['gender'] ?? null;
    $date = $data['date'] ?? null;
    $time = $data['time'] ?? null;
    $address = $data['address'] ?? null;

    if (!$email || !$name || !$date || !$time || !$address) {
        echo json_encode(["success" => false, "message" => "Required fields are missing"]);
        exit;
    }

    if (!sendAppointmentEmail($email, $name, $age, $gender, $date, $time, $address)) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to send email"]);
        exit;
    }

    $sql = "INSERT INTO appointments (email, name, age, gender, date, time, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$email, $name, $age, $gender, $date, $time, $address]);
        echo json_encode(["success" => true, "message" => "Appointment confirmed and email sent!"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error saving appointment"]);
    }
    ?>

<?php
require __DIR__ .'/bridgeSession.php';
require  __DIR__ . '/cors.php';
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
session_name("APPSESSID");
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["name"], $data["protein"], $data["carbs"], $data["fat"], $data["calories"])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=user14835_projectx;charset=utf8mb4","user14835_projectx","ProjectX", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $stmt = $pdo->prepare("
        INSERT INTO food_log (user_id, name, protein, carbs, fat, calories)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_SESSION["user_id"],
        $data["name"],
        $data["protein"],
        $data["carbs"],
        $data["fat"],
        $data["calories"]
    ]);

    echo json_encode(["success" => true]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}

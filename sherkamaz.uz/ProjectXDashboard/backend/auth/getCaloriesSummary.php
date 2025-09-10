<?php
session_name("DASHBOARDSESSID");
session_start();
require __DIR__ . "/../config/db.php";

// Get user ID
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// Weekly range
$monday = date("Y-m-d", strtotime("monday this week"));
$sunday = date("Y-m-d", strtotime("sunday this week"));

// Fetch calories consumed
$stmt = $pdo->prepare("SELECT DATE(created_at) as day, SUM(calories) as consumed FROM food_log WHERE user_id = ? AND DATE(created_at) BETWEEN ? AND ? GROUP BY day");
$stmt->execute([$userId, $monday, $sunday]);
$consumedData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Fetch calories burned
$stmt = $pdo->prepare("SELECT DATE(created_at) as day, SUM(calories) as burned FROM exercise WHERE user_id = ? AND DATE(created_at) BETWEEN ? AND ? GROUP BY day");
$stmt->execute([$userId, $monday, $sunday]);
$burnedData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Build complete dataset
$days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
$weekly = [
  "labels" => $days,
  "consumed" => [],
  "burned" => []
];

foreach ($days as $i => $dayLabel) {
    $day = date("Y-m-d", strtotime("$monday +$i days"));
    $weekly["consumed"][] = (int)($consumedData[$day] ?? 0);
    $weekly["burned"][] = (int)($burnedData[$day] ?? 0);
}

header("Content-Type: application/json");
echo json_encode($weekly);
?>

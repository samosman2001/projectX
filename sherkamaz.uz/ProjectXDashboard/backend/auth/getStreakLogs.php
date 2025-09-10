<?php
session_name("DASHBOARDSESSID");
session_start();
require  __DIR__ . "/../config/db.php";

$today = date("Y-m-d");
$stmt = $pdo->prepare("SELECT streak, created_at, user_id FROM exercise WHERE user_id = ?");

$stmt->execute([ $_SESSION['user_id'] ]);

$exercises = $stmt->fetchAll();
$latestTimestamp = 0;
$latestData = [];

foreach ($exercises as $exercise) {
    $createdDate = strtotime(date("Y-m-d", strtotime($exercise['created_at'])));
     
    if ($latestTimestamp < $createdDate) {
        $latestTimestamp = $createdDate;
        $latestData = [
            "streak" => $exercise["streak"],
            "created_at" => $exercise["created_at"],
            "user_id" => $exercise["user_id"]
        ];
    }

}



// Streak calculation
$yesterday = strtotime(date("Y-m-d", strtotime("-1 day")));

if ($latestTimestamp == $yesterday) {
   $latestData["streak"] += 1;
} else {
    $latestData["streak"] = 1;
}

$latestData["created_at"] = $today;

// Update
$stmt = $pdo->prepare("UPDATE exercise SET streak = ?, created_at = ? WHERE user_id = ?");
$stmt->execute([$latestData["streak"], $latestData["created_at"], $latestData["user_id"]]);

header("Content-Type: application/json");
echo json_encode($latestData);
?>

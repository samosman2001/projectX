<?php

session_name("DASHBOARDSESSID");
session_start();
try {
    $user_id = $_SESSION['user_id'];
    $pdo = new PDO(
        "mysql:host=localhost;dbname=projectx;charset=utf8mb4","root","",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
header('Content-Type:application/json ');
$stmt = $pdo->prepare("SELECT * FROM  food_log where user_id= ?");
$stmt->execute([$user_id]);
$foods = $stmt->fetchAll();
echo json_encode($foods);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
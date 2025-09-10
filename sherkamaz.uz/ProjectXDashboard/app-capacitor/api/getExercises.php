<?php
session_name("DASHBOARDSESSID");
session_start();
try
{
	$user_id = $_SESSION["user_id"];
	$pdo = new PDO("mysql:host=localhost;dbname=user14835_projectx;charset=utf8mb4","user14835_projectx","ProjectX",
[
 			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);
header("Content-Type: application/json");
$stmt = $pdo->prepare("SELECT * FROM exercise WHERE user_id = ? ");
$stmt->execute([$user_id]);
$exercise = $stmt->fetchAll();
echo json_encode($exercise);
}
catch(PDOException $pdo)
{
	die("Error : " . $pdo->getMessage());
}

?>
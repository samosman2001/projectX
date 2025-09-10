<?php
require  __DIR__ . '/cors.php';
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=user14835_projectx;charset=utf8mb4","user14835_projectx","ProjectX",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
header('Content-Type:application/json ');
$stmt = $pdo->prepare("SELECT * FROM  food");
$stmt->execute();
$foods = $stmt->fetchAll();
echo json_encode($foods);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>



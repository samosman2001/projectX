<?php
require __DIR__ .'/bridgeSession.php';
require  __DIR__ . '/cors.php';
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");
session_name("APPSESSID");
session_start();

$message = $_SESSION['toast_message'] ??($_GET['msg'] ?? "");

unset($_SESSION['toast_message']);

echo json_encode(["message"=> $message]);

?>
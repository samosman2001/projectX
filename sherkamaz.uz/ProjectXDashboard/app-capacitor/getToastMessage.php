<?php
header("Content-Type: application/json");
session_name("APPSESSID");
session_start();

$message = $_SESSION['toast_message'] ??($_GET['msg'] ?? "");

unset($_SESSION['toast_message']);

echo json_encode(["message"=> $message]);

?>
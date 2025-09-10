<?php
require __DIR__ . '/../../app-capacitor/api/cors.php';
$platform = $_GET['platform'] ?? 'dashboard';

if ($platform === "dashboard") {
    session_name("DASHBOARDSESSID");
} else {
    session_name("APPSESSID");
}

session_start();
session_unset();
session_destroy();

// Clear cookies
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
setcookie("remember_token", '', time() - 3600, "/");

$message = urlencode("You have successfully logged out!");

if ($platform === "dashboard") {
    // ✅ For web users, do server redirect
    header("Location: ../../../index.php?msg=$message");
    exit;
} else {
    // ✅ For mobile app, return JSON (do NOT redirect)
    header("Content-Type: application/json");
    echo json_encode([
        "success" => true,
        "message" => "You have successfully logged out!"
    ]);
    exit;
}
?>
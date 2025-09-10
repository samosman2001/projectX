<?php
require __DIR__ .'/bridgeSession.php';
require  __DIR__ . '/cors.php';

header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
//to start the session from localhost to www.sherkamaz.uz
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => 'www.sherkamaz.uz',
    'secure' => true,       // HTTPS only
    'httponly' => true,
    'samesite' => 'None'    // Required for cross-origin
]);
require __DIR__ . '/../../backend/config/db.php';
session_name("APPSESSID");
session_start();

header("Content-Type: application/json");
ini_set('display_errors', 0);

$_SESSION["platform"] = "app";

$_SESSION["response"] = [
    "authenticated"  => false,
    "message"  => "Invalid credentials",
    "redirect" => ""
];
$response = $_SESSION["response"] ;

// If POST request, process login
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['toast_message'] = "You have successfully logged in!";

       $response["authenticated"] = true;
       $response["message"] =$_SESSION['toast_message'];
       $response["redirect"] ="frontend/mainApp.html";

        if (!empty($_POST['remember'])) {
            $token = bin2hex(random_bytes(16));
            $hashedToken = hash('sha256', $token);

            $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
            $stmt->execute([$hashedToken, $user['id']]);

            setcookie("remember_token", $token, time() + 86400 * 30, "/", "", false, true);
        }
    }
} else {
    // Not a POST request → maybe page was reloaded after logout or failed login
    $response["message"] = $_SESSION['toast_message'] ?? ($_GET['msg'] ?? "");
    unset($_SESSION['toast_message']);
}

echo json_encode($response);
exit;

<?php
require __DIR__ .'/bridgeSession.php';
require __DIR__ . '/cors.php';

header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
session_name("APPSESSID");
session_start();

header("Content-Type: application/json");
//checkSession.php starts first before appLogin because appLogin is only activated when it's logged in
$_SESSION["response"] = [
    "authenticated"  => false,
    "message"  => "",
    "redirect" => ""
];

$response = $_SESSION["response"];
if(isset($_SESSION['user_id']))
{
$response["authenticated"] = true;
$response["redirect"]="frontend/mainApp.html";
 $_SESSION['toast_message']="You have already logged in";
 $response["message"] =$_SESSION['toast_message'];
}
else 
{
    $_SESSION['toast_message']="Permission denied";
    $response["redirect"]="../index.html";
    

}
error_log("✅ checkSession.php was hit");
error_log("Session ID: " . session_id());
error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'null'));
echo json_encode($response);

?>
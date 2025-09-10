
<?php 
session_name("APPSESSID");
session_start();

$_SESSION["platform"] = "app";

if(isset($_SESSION['user_id']))
{
  $_SESSION['toast_message']="You have already logged in";
  header("Location: /ProgrammingStuff/ProjectXDashboard/app-capacitor/mainApp.html");
}

$message = $_SESSION['toast_message'] ?? null;
unset($_SESSION['toast_message']);

//if message is not defined or clear to check in URL 
if(empty($message))
{    $message = $_GET['msg'] ?? "";
}include "../backend/auth/login.php";
?>



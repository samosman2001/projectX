<?php

session_name("APPSESSID");

session_start();
//if we go and click on "Back button" from exit.php(dashboard)
//to any of app web pages(like mainApp.php) then it $_SESSION changes to dashboard
//when on mainApp.php clicked on Logout button it goes to exit.php(dashboard)
//for that reason we should make it $_SESSION['platform'] = app if the user 
//going from the dashboard to app with back button or with url  
// echo $_SESSION['platform'];
$_SESSION['platform'] = "app";

if (!isset($_SESSION['user_id'])) {
    header("Location: /ProgrammingStuff/ProjectXDashboard/app-capacitor/appLogin.php");
    exit;
}

$message = $_SESSION["toast_message"]??null;
unset($_SESSION['toast_message']);


?>

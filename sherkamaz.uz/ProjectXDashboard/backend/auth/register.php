
<?php
session_set_cookie_params([
    'lifetime' => 0,
    'secure' => true, // set to false if not using HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);
$platform = $_GET['platform'] ;

if($platform == "dashboard")
    session_name("DASHBOARDSESSID");
else
    session_name("APPSESSID");
session_start();
require __DIR__ . '/../config/db.php';


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
  if ($password !== $confirmPassword) {
    echo "Passwords do 1 match.";
    exit;
}
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        if($_SESSION['platform']=="dashboard")
        header("Location: /../../../index.php#");
        else 
        header("Location: /ProgrammingStuff/ProjectXDashboard/app-capacitor/appLogin.php#");
        $_SESSION['toast_message'] = "Email already registered";
        exit;
    }

    // Hash password and insert
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES ( ?, ?)");
    $stmt->execute([$email, $hashedPassword]);
    if($_SESSION['platform']=="dashboard")
        header("Location:/../../../index.php");
    else 
        header("Location: /ProgrammingStuff/ProjectXDashboard/app-capacitor/appLogin.php");
$_SESSION['toast_message'] = "Account created successfully!";
    
    exit;
}
?>

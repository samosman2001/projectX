
<?php
require __DIR__ . '/../config/db.php';
$response = [
  "success" => false,
  "message" => "Invalid Credentials"
];
if($_SERVER['REQUEST_METHOD'] == "POST")
{
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT id,password_hash FROM users WHERE email = ?");
  $stmt->execute([$email]);
    $user = $stmt->fetch();
    
   
   if($user && password_verify($password,$user['password_hash']))
   {
    $_SESSION['user_id'] = $user['id'];

if(!empty($_POST['remember']))
{
 $token = bin2hex(random_bytes(16));
$hashedToken = hash("sha256", $token);

$stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
$stmt->execute([$hashedToken,$user["id"]]);
setcookie(
    "remember_token",   // name
    $token,             // value (raw, unhashed)
    time() + (86400 * 30),  // expiry: 30 days
    "/",                // path
    "",                 // domain
    false,              // secure (true if HTTPS)
    true                // httponly
);
}
$_SESSION['toast_message'] = "You have successfully logged in!";
   if ($_SESSION["platform"] == "dashboard"){
     header("Location: /ProjectXDashboard/frontend/main.php");
  }
//   else 
//   {
// $response["message"] = $_SESSION['toast_message'];
// echo json_encode($response);
//   }
//   exit;
   }

   else if($_SESSION["platform"] == "dashboard")
    {
        $_SESSION['toast_message'] = "Invalid Credentials";
   header("Location: /ProjectXDashboard/frontend/exit.php");
  }
//   else
//   {
//   //for capacitor frontend to fetch the toast_message
// $response["message"] = $_SESSION['toast_message'];
// $response['redirect'] = "/ProgrammingStuff/ProjectXDashboard/app-capacitor/frontend/appLogin.html";
// echo json_encode($response);

//  exit;
//   }




    }

  



?>
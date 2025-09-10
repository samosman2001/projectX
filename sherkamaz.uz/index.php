<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_name("DASHBOARDSESSID");
session_start();

$_SESSION["platform"] = "dashboard";
require __DIR__ . '/ProjectXDashboard/backend/config/db.php'; // Adjust if needed

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $hashedToken = hash('sha256', $token);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE remember_token = ?");
    $stmt->execute([$hashedToken]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        // optionally fetch more user data here and store in session
        $_SESSION['toast_message'] = "You have already logged in !";
        header("Location: /ProgrammingStuff/ProjectXDashboard/frontend/main.php");
        exit;
    }
}

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
$message = $_SESSION['toast_message'] ?? null;
unset($_SESSION['toast_message']);


//if message is not defined or clear to check in URL 
if(empty($message))
    {$message = $_GET['msg'] ?? null;

}


// ✅ Redirect if user is logged in
if (isset($_SESSION['user_id'])) {
  //to mention the user that you are already inside main.php
$_SESSION['toast_message'] = "You have already logged in !";
    header("Location: ProjectXDashboard/frontend/main.php");
    exit;
}
include "ProjectXDashboard/backend/auth/login.php";

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="ProjectXDashboard/frontend/styles/login.css">
	<link rel="icon" type="image/png" href="ProjectXDashboard/frontend/assets/icons/doublehelix.svg">

	<title>Log In </title>
</head>
<body>
	<main>
	<div class="container">
		<div class="leftBox">
			<div class="logo">
			<img src="ProjectXDashboard/frontend/assets/icons/doublehelix.svg">
			<p>Optimize your body,hack your life</p>
            </div>
		</div>
        <div class="rightBox">
        	<!-- <div class="SignupAndLogin">
           		<a href="exit.html">Log In</a>
           		<a href="register.html">Register</a>
        	</div> -->
        	<div class="loginBox" id ="loginBox">
  <form method="POST" action="index.php">
    <h2>Log in</h2>

    <label>
      <input type="email" name="email" placeholder="Email" required>
    </label>

    <div class="password">
      <input type="password" id ="passwordInput"name="password" placeholder="Password" required>
      <div class="eyeBackground">
      <div class="eyeIconCard" id="togglePassword">
        <img src="ProjectXDashboard/frontend/assets/icons/Eye-open.svg" alt="Show Password">
      </div>
    </div>
</div>

    <span>Forgot the password?</span>
    	<div class="remember">Remember me
      <div class="swtichButton">
	<label class="switch">
    <input type="checkbox" name="remember" value="yes">
  	  <div class="slider"></div>
  	</label>
	</div>
    </div>

    <button id ="login"type="submit">Log in</button>
  </form>
    <div class="register">
            Don’t have an account yet? <a href="#" id="showRegister">Register</a>
          </div>
</div>
        	<!-- Register Box  -->
 <div class="loginBox hiddenBox" id="registerBox">
  <!-- //attach register.php to traverse to another page when clicked  -->
          <form method="POST" action="ProjectXDashboard/backend/auth/register.php?platform=dashboard">
            <a href="#" id="backToLogin">&lt; Log In</a>
            <h2>Register</h2>
            <label><input type="email" id = "email" name ="email" placeholder="Email Address" required /></label>

            <div class="password">
              <input type="password" id="newPassword" name="newPassword" placeholder="New Password" required />
              <div class="eyeBackground">
                <div class="eyeIconCard" id="toggleNewPassword">
                  <img src="ProjectXDashboard/frontend/assets/icons/Eye-open.svg" alt="Show Password" />
                </div>
              </div>
            </div>

            <div class="password">
              <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required />
              <div class="eyeBackground">
                <div class="eyeIconCard" id="toggleConfirmPassword">
                  <img src="ProjectXDashboard/frontend/assets/icons/Eye-open.svg" alt="Show Password" />
                </div>
              </div>
            </div>

            <label><input type="tel" placeholder="Phone Number (optional)" /></label>
            <button  type="submit">Register</button>
          </form>
        </div>
        </div>

	</div>
	</main>

</body>

<div class="toast fail"></div>
<div class="toast success"></div>
<script>
	// const passwordInput = document.getElementById("passwordInput");
	// const togglePassword = document.getElementById("togglePassword");
	// const eyeIcon = togglePassword.querySelector("img");
	// togglePassword.style.transition = "opacity 0.4s ease";
	// togglePassword.addEventListener("click",()=>
	// {
	// 	const isHidden = passwordInput.type ==="password";
	// 	passwordInput.type =isHidden ?"text":"password";
	// 	eyeIcon.src = isHidden ? "assets/icons/Eye-closed.svg":"assets/icons/Eye-open.svg";
		 
	// 	if(isHidden)
	// 	{
	// 	 eyeIcon.src = "assets/icons/Eye-closed.svg";
  //                togglePassword.style.opacity = 0.5;
	// 	}

	// 	else 
	// 	{eyeIcon.src ="assets/icons/Eye-open.svg";
  //                 togglePassword.style.opacity = 1;
	// }});




  function setUpToggle(inputId,toggleId)
  {
    const input = document.getElementById(inputId);
    const toggle  = document.getElementById(toggleId);
    const icon = toggle.querySelector("img");

    toggle.addEventListener("click",()=>
    {
      const isHidden =input.type ==="password";
      input.type = isHidden?"text":"password";
      icon.src =isHidden?"ProjectXDashboard/frontend/assets/icons/Eye-closed.svg" : "ProjectXDashboard/frontend/assets/icons/Eye-open.svg";
    })
  }
  document.querySelector("#registerBox form").addEventListener("submit", (e) => {
  const newPassword = document.getElementById("newPassword").value;
  const confirmPassword = document.getElementById("confirmPassword").value;
  const email = document.getElementById('email').value;

  if (newPassword !== confirmPassword) {
   e.preventDefault();
      showToast("Passwords do not match");
  }

});

  setUpToggle("passwordInput","togglePassword");
  setUpToggle("newPassword","toggleNewPassword");
  setUpToggle("confirmPassword","toggleConfirmPassword");

const loginBox = document.getElementById("loginBox");
const registerBox = document.getElementById("registerBox");
const showRegister = document.getElementById("showRegister");
const backToLogin = document.getElementById("backToLogin");

showRegister.addEventListener("click" ,(e)=>
{
 e.preventDefault();
 loginBox.classList.add("hiddenBox");
 registerBox.classList.remove("hiddenBox");
});
backToLogin.addEventListener("click",(e)=>{
e.preventDefault();
loginBox.classList.remove("hiddenBox");
registerBox.classList.add("hiddenBox");
});


function showToast(message) {
  let toast;
  if(message.toLowerCase().includes("successfully"))
  toast = document.querySelector(".toast.success");
  else
  toast = document.querySelector(".toast.fail");
  
  toast.textContent = message;
  toast.classList.add("show");

  // Hide after 3s
  setTimeout(() => {
    toast.classList.remove("show");
  }, 3000);
}
<?php
 if(!empty($message)):
?>
showToast("<?= htmlspecialchars($message)?>");



<?php endif?>
//each time you click on back button it should be refreshed
window.onpageshow = function(e) {
  if (e.persisted) location.reload();
};
  // ✅ Remove the `msg` parameter from the URL

  history.replaceState(null, '', window.location.pathname);
</script>


</html>
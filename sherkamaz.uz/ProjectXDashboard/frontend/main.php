<?php
session_name("DASHBOARDSESSID");
session_start();
require __DIR__ . '/../backend/config/db.php';
$message = $_SESSION["toast_message"] ?? null;
unset($_SESSION['toast_message']);

if(!isset($_SESSION["user_id"]) &&isset ($_COOKIE['remember_token']))
{
    $token = $_COOKIE['remember_token'];
    $hashedToken = hash("sha256", $token);
    $stmt = $pdo->prepare("SELECT id FROM users WHERE remember_token= ?");
    $stmt ->execute([$hashedToken]);
    $user = $stmt->fetch();
    if($user)
    {
        $_SESSION['user_id'] = $user["id"];
    }
}

// Block cached page from being shown
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");



// ✅ Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /ProjectXDashboard/frontend/exit.php");
    exit;
}
$height = $_POST['height_cm'] ?? null;
$weight = $_POST['weight_kg'] ?? null;
$bmi = $height > 0 ? round($weight / (($height / 100) ** 2), 1) : null;

$bmiStatus = '';
$bmiImage = '';
if ($bmi) {
    if ($bmi < 18.5) {
        $bmiStatus = "You're underweight";
        $bmiImage = "assets/icons/Male-Clothes-thin.svg";
    } elseif ($bmi < 25) {
        $bmiStatus = "You're healthy";
        $bmiImage = "assets/icons/Male-Clothes.svg";
    } else {
        $bmiStatus = "You're overweight";
        $bmiImage = "assets/icons/Male-Clothes-overWeight.svg";
    }
}
function getBMIPercent($bmi) {
    $min = 15;
    $max = 35;
    if (!$bmi || $bmi < $min) return 0;
    if ($bmi > $max) return 100;
    return round((($bmi - $min) / ($max - $min)) * 100, 1);
}

function calculateAnthropoAge($age, $sex, $height, $weight, $waist) {
    if (!$height || !$weight || !$waist || !$age || !$sex) return null;

    $bmi = $weight / (($height / 100) ** 2);
    $waistRatio = $waist / $height;

    // Ideal ranges
    $idealBMI = 22; // Ideal BMI mid-range
    $idealWaistRatio = $sex === 'male' ? 0.45 : 0.42;

    // Deviations
    $bmiPenalty = abs($bmi - $idealBMI);
    $waistPenalty = max(0, $waistRatio - $idealWaistRatio) * 100;

    // Total biological age
    $penaltyScore = ($bmiPenalty * 0.8) + ($waistPenalty * 1.2);
    $anthroAge = $age + $penaltyScore;

    return round($anthroAge, 1);
}


$age = $_POST['age'] ?? null;
$sex = $_POST['gender'] ?? null;
$waist = $_POST['waist'] ?? null;
$anthropoAge = calculateAnthropoAge($age, $sex, $height, $weight, $waist);
?>

<!-- Main Page -->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale = 1.0">
	<title>Main</title>
	<link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/toast.css">
    <link rel="icon" type="image/png" href="assets/icons/main.svg">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>
    <div class="container">
<aside>
    
        <nav class="navigation">
            
            <div class="upperPart">
            <div class="icon"></div>
            <a href="#" class="btn doublehelix" ><img src="assets/icons/doublehelix.svg" alt="Doublehelix" ></a>
            <a href="main.php" class="btn main"><img src="assets/icons/main.svg" alt="Main"></a>
            <a href="nutrition.php" class="btn nutrition"><img src="assets/icons/Nutrition.svg" alt="nutrition" ></a>
            <a href="exercise.php" class="btn exercise"><img src="assets/icons/dumbell.svg" alt="Exercise" ></a>
            <a href="settings.html" class="btn settings"><img src="assets/icons/Settings.svg" alt="Settings" ></a>
            <a href="calendar.html" class="btn calendar"><img src="assets/icons/Calendar.svg" alt="Calendar" ></a>
            <a href="progressChart.html" class="btn progressChart"><img src="assets/icons/Statistics.svg" alt="progressChart" ></a>
            </div>
        <div class="lowerPart">
            <a href="help.html" class="btn"><img src="assets/icons/Help.svg" alt="Help" ></a>
            <a href="../backend/auth/logout.php?platform=dashboard" class="btn"><img src="assets/icons/Exit.svg" alt="Exit" ></a>
            <a href=""></a> 
        </div>
        </nav>
    
</aside>
	<main>

		<div class="header">
			<h2>Health Overview</h2>
            <div class="tools">
                <div class="search">
                    <img src = "assets/icons/search.svg">
                </div>
                  <div class="notification">
                    <img src = "assets/icons/notification.svg">
                </div>
            </div>
		</div>
        <div class="content">
        <div class="modelContent">
		<div class="modelInput">Model Inputs</div>
            <div class="cards modelCard">
                
        <span>To estimate either AnthropoAge or S-AnthropoAge,please introduce the following data:</span>
    <form action="main.php" method="POST">
      <div class="textContainer">
        <div class="textfield">
        <label for="height_cm">Height</label>
        <input type="number" id ="height_cm" name="height_cm" placeholder="Height (cm)" required>
        </div>

        <div class="textfield">
        <label for="weight_kg">Weight</label>
        <input type="number" id ="weight_kg" name="weight_kg" placeholder="Weight (kg)">
        </div>

        <div class="textfield">
        <label for="age">Chronoglogical age</label>
        <input type="number" id ="age" name="age">
        </div>

        <div class="textfield">
        <label for="waist">Waist Circumference (cm)</label>
        <input type="number" id ="waist" name="waist">
        </div>
     </div>
 
        <div class="text">
        <span>Sex</span>
     </div>
       <div class="toggle-group">
  <input type="radio" id="female" name="gender" value="female" hidden >
  <label for="female">Female</label>

  <input type="radio" id="male" name="gender" value="male" hidden >
  <label for="male">Male</label>
       </div>
         <div class="text">
        <span>Race/Ethnicity</span>
       </div>

        <!-- Options for Ethnicity  -->
        <div class="toggle-group">
        	<input type="radio" id ="white" name="enthicity" value = "white" hidden>
           <label for="white">White</label>
        
        	<input type="radio" id ="black" name="enthicity" hidden>
        <label for="black">Black</label>

      <input type="radio" id ="mexican" name="enthicity" hidden>
    <label for="mexican">Mexican</label>

     <input type="radio" id ="other" name="enthicity" hidden>
     <label for="other">Other</label>
        </div>
  	   <div class="text">
        <span>Only Estimate S-Anthropoage</span>
       </div>
    <div class="swtichButton">
	<label class="switch">
    <input type="checkbox">
  	  <div class="slider"></div>
  	</label>
	</div>

<button type="submit">Submit</button>
        </form>
        <form>
    <div class="text">
        <span>For estimation of full Anthropoage ,please also introduce : </span>
</div> 
   
   	<div class="textContainer">
		<div class="textfield">
        <label for="username">Chronoglogical</label>
        <input type="text" id ="username" name="username">
        </div>

        <div class="textfield">
        <label for="username">Chronoglogical</label>
        <input type="text" id ="username" name="username">
        </div>

        <div class="textfield">
        <label for="username">Chronoglogical</label>
        <input type="text" id ="username" name="username">
        </div>

        <div class="textfield">
        <label for="username">Chronoglogical</label>
        <input type="text" id ="username" name="username">
        </div>
    </div>  
    <button type="submit">Submit</button>
</form>
</div>
</div>

<!-- BMI card -->
<div class="BmiAndResults">
<div class="cards BMI">
    <div class="titleAndSelector">
        <div class="title">
<h2 >BMI calculator</h2></div>
<div class="genderSelector">
<label for="gender">Gender:</label>
<select id="gender" name="gender">
  <option value="lastWeek">Last Week</option>
  <option value="lastMonth">Last Month</option>
  <option value="thisWeek">This Month</option>
  
</select>
</div>
</div>
<div class="inputs">
<div class="leftColumn">
<div class="bmiMetrics height-metrics">
    <div class="ruler">
        <div class="tick-group">
            <div class="tick"></div>
            <div class="tick"></div>
            <div class="tick"></div>
            <div class="tick red"></div>
            <div class="tick"></div>
            <div class="tick"></div>
            <div class="tick"></div>
            <div class="tick"></div>
            <div class="tick"></div>
        </div>
    </div>
    <div class="bottom-row">
        <div class="label">Height</div>
        <div class="value"><?= $height ?? '' ?> cm</div>
    </div>
</div>
<div class="bmiMetrics weight-metrics">
    <div class="ruler">
        <div class="tick-group">
            <div class="tick"></div>
            <div class="tick"></div>
            <div class="tick"></div>
            <div class="tick red"></div>
            <div class="tick"></div>
            <div class="tick"></div>
            <div class="tick"></div>
            <div class="tick"></div>
            <div class="tick"></div>
        </div>
    </div>
    <div class="bottom-row">
        <div class="label">Weight</div>
        <div class="value"><?=$weight ?> kg</div>
    </div>
</div>
</div>
<div class="rightColumn bmiCard">
	<div class="bmiTitle">Body Mass Index (BMI)</div>
    <div class="statusAndResult">
    <div class="bmiResult"><?= $bmi ?? '--' ?></div>
<div class="bmiStatus"><?= $bmiStatus ?? '' ?></div>
</div>
  <div class="bmiBar" style="position: relative;">
  <div class="bmiDot" style="position: absolute; top: -6px; left: <?= getBMIPercent($bmi) ?>%; width: 12px; height: 12px; background-color: red; border-radius: 50%; border: 2px solid white;"></div>
</div>

	<div class="bmiLabels">
		<span>15</span>
 		<span>18.5</span>
 		<span>25</span>
 		<span>30</span>
 		<span>35</span>
	</div>
</div>
</div>

<div class="line">
<hr class="hrLine">
</div>
<div class="bMeasurementsAndIllustration">
<div class="bMeasurements">
    <h2><span>Body Measurements</span></h2>

<div class="timeOfChecking">
    <span>Last Checked 2 Days ago</span>
</div>
<div class="bShape">
    <span>Inverted Triangle 2 Days Ago</span>
</div>
<div class="metrics">
    <div class="metric">
        <span>Chest (cm)</span>
        <div class="result chestResult">
            <span>44.5</span>
            <div class="upSticks">
              <div class="upStick arrowUpLeftStick"></div>
              <div class="upStick arrowUpRightStick"></div>
              <div class="upStick arrowUpMiddleStick"></div>
            </div>
        </div>
    </div>

    <div class="metric">
        <span>Waist (cm)</span>
        <div class="result waistResult">
            <span>34</span>
            <div class="downSticks">
              <div class="downStick arrowDownLeftStick"></div>
              <div class="downStick arrowDownRightStick"></div>
              <div class="downStick arrowDownMiddleStick"></div>
            </div>
        </div>
    </div>

    <div class="metric">
        <span>Chest (cm)</span>
        <div class="result chestResult">
            <span>42.5</span>
            <div class="upSticks">
              <div class="upStick arrowUpLeftStick"></div>
              <div class="upStick arrowUpRightStick"></div>
              <div class="upStick arrowUpMiddleStick"></div>
            </div>
        </div>
    </div>

</div>
</div>


<div class="illustration">
     <img src="<?= $bmiImage ?: 'assets/icons/Male-Clothes.svg' ?>" alt="bodyIllustration">
</div>

</div>

</div>
<div class="cards anthopoageResults">
    <div class="anthopoageCard">
        <div class="description">
          <span><?= $anthropoAge ?? '--' ?></span>
        </div>
        <div class="image">
            <img src=".svg" alt="image">
        </div>
    </div>

    <div class="anthopoageCard">
        <div class="description">
            <span>-2.3</span>
            <span>AnthropoageAcceleration</span>
        </div>
        <div class="image">
            <img src=".svg" alt ="AhtropoageAccelaration">
        </div>
    </div>

     <div class="anthopoageCard">
        <div class="description">
            <span>NON-ACCELERATED</span>
            <span>Aging rate</span>
        </div>
        <div class="image">
            <img src=".svg" alt ="NON-ACCELERATED AGING">
        </div>
    </div>

</div>

</div>
</div>
	</main>
     
    </div>

  <div class="toast fail"></div>
    <div class="toast success"></div>
</body>

<script>
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
</script>
</html>
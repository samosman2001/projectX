<?php
session_name("DASHBOARDSESSID");
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nutrition</title>
<link rel="stylesheet" href="styles/main.css">
<link rel="icon" type="image/png" href="assets/icons/Nutrition.svg">

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
</head>
<style>

    main {
      flex-grow: 1;
      padding: 2rem;
    }
    .nutrition-section h2 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    .nutrition-log {
      background: white;
      padding: 1rem;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-bottom: 2rem;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 0.75rem;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      font-weight: bold;
    }
    .summary-boxes {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }
    .summary-box {
      flex: 1;
      min-width: 150px;
      padding: 1rem;
      text-align: center;
      color: #000;
      font-weight: bold;
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .summary-box.green { background-color: #00cc44; color: white; }
    .summary-box.yellow { background-color: #ffff33; }
    .summary-box.orange { background-color: #ff9900; }
    .summary-box.gray { background-color: #e0e0e0; }
  </style>
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
    <div class="nutrition-section">
      <h2>Total calories</h2>
      <div class="nutrition-log">
        <h3>Nutrition Log</h3>
        <table>
          <thead>
            <tr>
              <th>Time</th>
              <th>Meal</th>
              <th>Protein</th>
              <th>Carbs</th>
              <th>Fat</th>
              <th>Calories</th>
            </tr>
          </thead>
          <!-- This is a body of the table and it will be refreshing as there's an update in the database  -->
          <tbody id="nutritionBody">
            <!-- <tr>
              <td><strong>08:00am</strong></td>
              <td>Bread</td>
              <td>2g</td>
              <td>2g</td>
              <td>80 kcal</td>
            </tr>
            <tr>
              <td><strong>08:00am</strong></td>
              <td>Cabbage</td>
              <td>1g</td>
              <td>1g</td>
              <td>22 kcal</td>
            </tr>
            <tr>
              <td><strong>08:00am</strong></td>
              <td>Red Meat</td>
              <td>25g</td>
              <td>25g</td>
              <td>120 kcal</td>
            </tr> -->
          </tbody>
        </table>
      </div>
      <div class="summary-boxes">
        <div class="summary-box green" id="totalCalories">🔥 Calories<br>x kcal</div>
        <div class="summary-box yellow" id="totalProtein">🥚 Protein<br>x g</div>
        <div class="summary-box orange" id="totalFat">🥑 Fat<br>x g</div>
        <div class="summary-box gray" id="totalCarbs">🍞 Carbs<br>x g</div>
      </div>
    </div>
  </main>
  <script>
    fetch("../app-capacitor/api/getFoodLogs.php")
    .then(res=>res.json())
    .then(data=>
    {
    const btable = document.getElementById("nutritionBody");
    let totalCalories =0,totalProtein=0,totalCarbs=0,totalFat=0;
    data.forEach(item=>
    {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td><strong>${item.time}</strong></td>
        <td>${item.name}</td>
         <td>${item.protein}</td>
        <td>${item.carbs}</td>
         <td>${item.fat}</td>
         <td>${item.calories}</td>
    `;
    btable.appendChild(row);
   totalCalories += parseFloat(item.calories);
   totalProtein +=parseFloat(item.protein);
   totalCarbs += parseFloat(item.carbs);
   totalFat += parseFloat(item.fat);
   console.log(item.fat);
    });
    document.getElementById("totalCalories").innerHTML = 
  `🔥 Calories  <br>`  + totalCalories.toFixed(2) ;
    document.getElementById("totalProtein").innerHTML = `🥚 Protein <br>`+totalProtein.toFixed(2)+`g`;
    document.getElementById("totalFat").innerHTML ='🥑 Fat <br>' +totalFat.toFixed(2)+`g`;
    document.getElementById("totalCarbs").innerHTML =`🍞 Carbs`+ totalCarbs.toFixed(2)+`g`;
    }).catch(err=>console.log("Server Error :".err))

  </script>
</body>
</html>
<!DOCTYPE html>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Exercise</title>
	<link rel="stylesheet" href="styles/main.css">
	<link rel="icon" type="image/png" href="assets/icons/dumbell.svg">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
      <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<style>
    * {
      box-sizing: border-box;
      font-family: 'Lato', sans-serif;
      margin: 0;
      padding: 0;
    }
    body {
      display: flex;
      background-color: #fcfafa;
      color: #333;
    }

    aside img {
      width: 100%;
    }
    main {
      flex-grow: 1;
      padding: 2rem;
    }
    .mode-switch {
      margin-bottom: 1rem;
    }
    .mode-switch button {
      border: none;
      padding: 0.5rem 1rem;
      font-weight: bold;
      margin-right: 1rem;
      border-radius: 10px;
      background-color: #eee;
      opacity: 0.55;
    }
    .mode-switch button.active {
      background-color: #4CAF50;
      color: #fff;
      opacity: 1;
    }
    .weekly-activity {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
      gap: 2rem;
    }
    .card {
      background-color: #fff;
      padding: 1rem;
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
   width: 100%;
    }
    .card.full-width {
      grid-column: 1 / -1;
    }
    h2 {
      margin-bottom: 1rem;
    }
    canvas {
      width: 100% !important;
      height: auto !important;
    }
    .twoCards
    {
        display: flex;
    }
  </style>
<body>
  <div id="app">
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
			<h2>Exercise</h2>
            <div class="tools">
                <div class="search">
                    <img src = "search.svg" alt="search">
                </div>
                  <div class="notification">
                    <img src = "notification.svg">
                </div>
            </div>
            <div class="optionalTools">
            	<div class="avatar">
            		<img src="avatar.svg" alt="avatar">
            	</div>
            	<div class="filter">
            		<img src="filter.svg" alt="filter">
            	</div>
            </div>
		</div>
     <main>
    <div class="mode-switch">
      <button  data-mode="gym">Gym</button>
      <button data-mode="home">Home</button>
    </div>
    <div class="weekly-activity">
      <h2>Weekly Activity</h2>
      <div class="dashboard">
         <div class="card">
          <h3>Logged Exercises</h3>
          <ul id="exerciseList"></ul>
        </div>
        <div class="card">
          <h3>Workouts Per Week</h3>
          <canvas id="workoutChart"></canvas>
        </div>
    <div class="twoCards">
        <div class="card">
          <ul id="mostFrequentExercises" >Most frequent exercises</h3>
          <ol>
          <li v-for="exercise in top3" :key="exercise.name">{{exercise.name}}-{{exercise.value}}
</li>
          <!--   <li>Push-up squats - 5</li>
            <li>Squats - 6</li>
            <li>Plank - 4</li>
           -->
         </ol>


        </div>
        <div class="card">
          <h3>Streak tracker</h3>
          <p v-if="streak!=0">
            You’re on a {{streak}} day streak
          <p v-else>No Streaks</p>
</p>
        </div>
    </div>
        <div class="card full-width">
          <h3>Customer Map</h3>
          <p><strong>450 kcal left</strong><br/>Calorie Goal: 2,000 kcal</p>
          <canvas id="calorieChart"></canvas>
        </div>
      </div>
    </div>

  </main>
</div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
const {createApp} = Vue;
createApp({

data()
{
  return {
    top3 : [],
    counts : [],
    streak: 0
}
  ;
},
mounted(){
 fetch("../app-capacitor/api/getExercises.php")
      .then(res=>res.json())
      .then(data=>{
       let now = new Date();
       now = Math.floor(now.getTime() / 1000);
       console.log("Current seconds : "+ now);
         let max = 0;
     data.forEach(item=>
     {
         
    this.counts[item.name]=(this.counts[item.name] || 0) + 1;
                

     });
     

         
     const top3= Object.entries(this.counts).sort((a,b)=>b[1] - a[1]).slice(0,3).map(([name,value])=>({name,value}));
      
      this.top3 = top3;
      
        });

      fetch("../backend/auth/getStreakLogs.php")
      .then(res => res.json())
      .then(data => {
        this.streak = data.streak;
        console.log(this.streak);
        this.created_at = data.created_at;

      })
      .catch(err => {
        console.error("Failed to fetch streak:", err);
      });
      
}

}).mount("#app");

let workoutChart = null;
    function loadExercises(selectedMode) {
  fetch("../app-capacitor/api/getExercises.php")
    .then(res => res.json())
    .then(data => {
      
      const sessions = [0, 0, 0, 0, 0, 0, 0];
      const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
      const list = document.getElementById("exerciseList");
      //for most frequent exercises tab 
      const mostFrequent = document.getElementById("mostFrequentExercises"); 
      let counts=[];
      // Get Monday and Sunday of the current week
      const now = new Date();
      const day = now.getDay();
      const monday = new Date(now);
      monday.setDate(now.getDate() + (day === 0 ? -6 : 1 - day));
      monday.setHours(0, 0, 0, 0);
      const sunday = new Date(monday);
      sunday.setDate(monday.getDate() + 6);
      sunday.setHours(23, 59, 59, 999);

      list.innerHTML = data
        .filter(entry => entry.mode === selectedMode)
        .filter(entry => {
          const [year, month, day] = entry.created_at.split("-");
          const date = new Date(year, month - 1, day, 12);
          return date >= monday && date <= sunday;
        })
        .map(entry => {
          const [year, month, day] = entry.created_at.split("-");
          const date = new Date(year, month - 1, day, 12);
          const jsDay = date.getDay();
          const index = (jsDay + 6) % 7;
          sessions[index] += 1;

          return `<li>${entry.name} - ${entry.sets} sets, ${entry.reps} reps @ ${entry.weight}</li>`;
        })
        .join("");
      
      const ctx1 = document.getElementById('workoutChart').getContext('2d');
      if (workoutChart) {
  workoutChart.destroy();
  
}
      workoutChart = new Chart(ctx1, {
  type: 'bar',
  data: {
    labels: days,
    datasets: [{
      label: `${selectedMode === 'gym' ? 'Gym' : 'Home'} Workouts`,
      data: sessions,
      backgroundColor: '#3366FF',
      borderRadius: 8
    }]
  },
  options: {
    scales: { y: { beginAtZero: true } }
  }
});
    });
}
    
    const ctx2 = document.getElementById('calorieChart').getContext('2d');
    new Chart(ctx2, {
      type: 'bar',
      data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [
          {
            label: 'Consumed',
            data: [1500, 1600, 1300, 900, 1200, 1700, 1800],
            backgroundColor: 'red',
            borderRadius: 8
          },
          {
            label: 'Burned',
            data: [1200, 2000, 1500, 1000, 1300, 1600, 1900],
            backgroundColor: 'gold',
            borderRadius: 8
          }
        ]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    document.querySelectorAll(".mode-switch button").forEach(btn => {
  btn.addEventListener("click", () => {
    document.querySelectorAll(".mode-switch button").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    const selectedMode = btn.getAttribute("data-mode");
    loadExercises(selectedMode);
  });
});
  </script>
</body>
</html>

</body>
</html>
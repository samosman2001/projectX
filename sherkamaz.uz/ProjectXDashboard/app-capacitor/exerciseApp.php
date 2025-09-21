<?php
session_name("APPSESSID");
session_start();
 $_SESSION['user_id'] = "app";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exercise - Leg Day</title>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <link rel="stylesheet" type="text/css" href="styles/mainApp.css">
  <link rel="stylesheet" type="text/css" href="styles/exerciseApp.css">
</head>
<body>
  <div id="app">
    <header>
      <img src="your-logo.png" alt="Logo">
    </header>
<main>
    <div class="mode-switch">
      <button :class="{active: mode==='Gym'}" @click="mode='Gym'">Gym</button>
      <button :class="{active: mode==='Home'}" @click="mode='Home'">Home</button>
    </div>

    <h2>Leg Day</h2>

    <div class="exercise-box" v-for="exercise in exercises" :key="exercise.name">
      <div class="exercise-title">Exercise: {{ exercise.name }}</div>
      <div class="set-row" style="font-weight: bold;">
        <div>Sets</div><div>Reps</div><div>Weight</div><div>Status</div>
      </div>
      <div class="set-row" v-for="(set, i) in exercise.sets" :key="i">
        <input v-model="set.sets" type="number" />
        <input v-model="set.reps" type="number" />
        <input v-model="set.weight" type="number" />
        <input v-model="set.status" type="text" />
      </div>
      <button class="add-set-btn" @click="addSet(exercise)">+ Add Set</button>
    </div>

    <div class="actions">
      <button class="done-btn">Done</button>
      <button class="finish-btn">Finish</button>
    </div>
</main>
<footer>
     <nav class="bottom-nav">
    <div class="item">
      <a href="nutritionApp.html">
      <div class="icon">🍎</div>
      <div>Nutrition</div>
    </a>
    </div>
    <div class="item">
      <a href="exerciseApp.html">
      <div class="icon">🏋️</div>
      <div>Exercise</div>
     </a>
    </div>

       <div class="home-button">
 <a href="mainApp.php">
    🏠
  </a></div>

    <div class="item">
      <div class="icon">🗓️</div>
      <div>Calendar</div>
    </div>
    <div class="item">
      <div class="icon">📈</div>
      <div>Progress</div>
    </div>
  </nav>
  </div>
</footer>
  <script>
    const { createApp } = Vue;
    createApp({
      data() {
        return {
          mode: 'Gym',
          exercises: [
            { name: 'Squats', sets: [{ sets: 3, reps: 8, weight: 0, status: '' }] },
            { name: 'Walking Lunges', sets: [{ sets: 3, reps: 8, weight: 0, status: '' }] },
            { name: 'Romanian Deadlift', sets: [{ sets: 3, reps: 8, weight: 0, status: '' }] },
            { name: 'Barbell squat', sets: [{ sets: 3, reps: 8, weight: 0, status: '' }] },
          ]
        }
      },
      methods: {
        addSet(exercise) {
          exercise.sets.push({ sets: '', reps: '', weight: '', status: '' });
        }
      }
    }).mount('#app');
  </script>
</body>
</html>


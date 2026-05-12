<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Food Calorie Calculator with Meal Charts</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
:root {
  --bg: #f8f9fa;
  --card: #ffffff;
  --accent: #2b8a3e;
  --accent-light: #d3f9d8;
  --text: #333;
  --muted: #666;
}
body { margin:0; font-family:"Segoe UI",Arial,sans-serif; background:var(--bg); color:var(--text);}
.container { display:grid; grid-template-columns:250px 1fr; min-height:100vh; }
.card { background:var(--card); padding:16px; margin:12px; border-radius:12px; box-shadow:0 2px 6px rgba(0,0,0,0.08); }
h1,h2,h3 { margin-top:0; color:var(--accent); }
.muted { color:var(--muted); font-size:20px; }
.cat { cursor:pointer; padding:6px 10px; border-radius:8px; transition:0.2s; }
.cat:hover { background: var(--accent-light); }
.cat.active { color: var(--accent); font-weight:600; background: var(--accent-light); }
#foodList .item { padding:10px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; cursor:pointer; }
#foodList .item:hover { background:#f1f3f5; }
.meal-selector { margin:10px 0; }
.meal-selector button { margin-right:6px; padding:6px 10px; border-radius:6px; border:none; cursor:pointer; background:var(--accent-light); color:var(--accent); }
.meal-selector button.active { font-weight:600; background:var(--accent); color:#fff; }
input[type=number] { width:60px; margin-left:6px; }
.summary { margin-top:12px; }
.meal-summary { margin-top:10px; border-top:1px dashed #ccc; padding-top:10px; }
#chartContainer { margin-top:20px; display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.meal-chart { background:var(--card); padding:12px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.08); position:relative; }
.progress { width: 100%; background: #eee; border-radius: 6px; overflow: hidden; height: 16px; position: relative; }
.bar { height: 100%; width: 0%; border-radius: 6px; transition: width 0.5s; position: relative; }
.bar-label { position: absolute; right: 4px; color: #fff; font-size: 12px; font-weight: bold; }
#progCal { background: rgba(43,138,62,0.7); }
#progPro { background: rgba(255,99,132,0.7); }
#progCarb { background: rgba(54,162,235,0.7); }
#progFat { background: rgba(255,206,86,0.7); }
</style>
</head>
<body>
<div class="container">
  
  <div class="card">
    <h2>User Info</h2>
    <label>Age: <input type="number" id="age" value="25"></label><br><br>
    <label>Sex: 
      <select id="sex">
        <option value="male">Male</option>
        <option value="female">Female</option>
      </select>
    </label><br><br>
    <label>Weight (kg): <input type="number" id="weight" value="70"></label><br><br>
    <label>Height (cm): <input type="number" id="height" value="170"></label><br><br>
    <button onclick="calculateBMR()">Set Personal Target</button>
    <p class="muted" id="bmrText"></p>

    <h2>Categories</h2>
    <div style="display:grid;grid-template-columns:1fr;gap:6px">
      <div class="cat active" data-cat="All">All Foods</div>
      <div class="cat" data-cat="Fruits">Fruits</div>
      <div class="cat" data-cat="Vegetables">Vegetables</div>
      <div class="cat" data-cat="Dairy & Eggs">Dairy & Eggs</div>
      <div class="cat" data-cat="Nuts & Seeds">Nuts & Seeds</div>
      <div class="cat" data-cat="Grains & Staples">Grains & Staples</div>
      <div class="cat" data-cat="Indian Foods">Indian Foods</div>
      <div class="cat" data-cat="Snacks & Sweets">Snacks & Sweets</div>
      <div class="cat" data-cat="Light Foods">Light Foods</div>
    </div>
    <p class="muted">Click a category to filter foods. Click a food to add to meal.</p>
  </div>

  
  <div class="card">
    <h1>Food List</h1>
    <div class="meal-selector">
      <button class="active" onclick="selectMeal('Breakfast', event)">Breakfast</button>
      <button onclick="selectMeal('Lunch', event)">Lunch</button>
      <button onclick="selectMeal('Snacks', event)">Snacks</button>
      <button onclick="selectMeal('Dinner', event)">Dinner</button>
    </div>
    <div id="foodList"></div>

    <h2>Selected Foods</h2>
    <button onclick="removeAll()">Remove All (Current Meal)</button>
    <button onclick="resetAll()">Reset All Meals</button>
    <div id="selectedFoods"></div>

    <div class="summary" id="mealSummaries"></div>

    <!-- Meal Charts -->
    <div id="chartContainer">
      <div class="meal-chart">
        <h3>Breakfast</h3>
        <canvas id="chartBreakfast" height="150"></canvas>
      </div>
      <div class="meal-chart">
        <h3>Lunch</h3>
        <canvas id="chartLunch" height="150"></canvas>
      </div>
      <div class="meal-chart">
        <h3>Snacks</h3>
        <canvas id="chartSnacks" height="150"></canvas>
      </div>
      <div class="meal-chart">
        <h3>Dinner</h3>
        <canvas id="chartDinner" height="150"></canvas>
      </div>
      <div class="meal-chart" style="grid-column: span 2;">
        <h3>Total Daily Intake</h3>
        <canvas id="chartTotal" height="150"></canvas>
        <div id="progressBars" style="margin-top:12px;">
          <b>Daily Progress:</b>
          <div style="margin:4px 0;">Calories: <div class="progress"><div id="progCal" class="bar"><span class="bar-label"></span></div></div></div>
          <div style="margin:4px 0;">Protein: <div class="progress"><div id="progPro" class="bar"><span class="bar-label"></span></div></div></div>
          <div style="margin:4px 0;">Carbs: <div class="progress"><div id="progCarb" class="bar"><span class="bar-label"></span></div></div></div>
          <div style="margin:4px 0;">Fat: <div class="progress"><div id="progFat" class="bar"><span class="bar-label"></span></div></div></div>
        </div>
      </div>
    </div>

    <!-- Burn Charts -->
    <div id="burnCharts" style="margin-top:20px; display:grid; grid-template-columns:1fr 1fr; gap:16px;">
      <div class="meal-chart">
        <h3>Distance Needed to Burn Calories (km)</h3>
        <canvas id="distanceChart" height="150"></canvas>
      </div>
      <div class="meal-chart">
        <h3>Time Needed to Burn Calories (min)</h3>
        <canvas id="timeChart" height="150"></canvas>
      </div>
      <div class="meal-chart" style="grid-column: span 2;">
        <h3>Summary</h3>
        <div id="burnSummary" class="muted"></div>
      </div>
    </div>

  </div>
</div>

<script>
const categories = {
  "Fruits":["Apple","Banana","Mango","Orange","Papaya","Grapes","Watermelon","Strawberries","Avocado","Almonds"],
  "Vegetables":["Broccoli","Carrot","Cauliflower","Cabbage","Spinach","Tomatoes","Onion","Cucumber","Mushroom","Potato","Drumstick","Ladyfinger","Bitter Gourd","Bottle Gourd","Pumpkin","Beetroot","Green Peas","Sweet Potato","Brinjal"],
  "Dairy & Eggs":["Milk","Cheese","Curd","Paneer","Butter","Ghee","Egg (boiled)","Egg Omelette"],
  "Nuts & Seeds":["Cashews","Walnuts","Peanuts","Pistachios","Sunflower Seeds","Flaxseed","Chia Seeds","Almonds"],
  "Grains & Staples":["Rice","Brown Rice","Chapati","Paratha","Poori","Oats","Cornflakes","Whole Wheat","Whole Grain Cereal"],
  "Indian Foods":["Idli","Dosa","Upma","Poha","Biryani","Pulao","Dal","Rajma","Chole","Beef","Chicken(cooked)","Chicken(raw)","Mutton","Fish"],
  "Snacks & Sweets":["Samosa","Pakora","Burger","Pizza","Sandwich","Pasta","Ice Cream","Chocolate","Laddu","Jalebi","Maggi","Pav Bhaji","Bhel Puri","Pani Puri","Gulab Jamun","Kheer","Halwa","Cake","Biscuits","Popcorn"],
  "Light Foods":["Soup","Salad","Sprouts"]
};
const nutrition = {
  "Apple": { "cal": 52, "pro": 0.3, "carb": 14, "fat": 0.2 },
  "Almonds": { "cal": 579, "pro": 21, "carb": 22, "fat": 50 },
  "Avocado": { "cal": 160, "pro": 2, "carb": 9, "fat": 15 },
  "Banana": { "cal": 89, "pro": 1.1, "carb": 23, "fat": 0.3 },
  "Grapes": { "cal": 69, "pro": 0.7, "carb": 18, "fat": 0.2 },
  "Mango": { "cal": 60, "pro": 0.8, "carb": 15, "fat": 0.4 },
  "Orange": { "cal": 47, "pro": 0.9, "carb": 12, "fat": 0.1 },
  "Papaya": { "cal": 43, "pro": 0.5, "carb": 11, "fat": 0.3 },
  "Strawberries": { "cal": 33, "pro": 0.7, "carb": 8, "fat": 0.3 },
  "Watermelon": { "cal": 30, "pro": 0.6, "carb": 8, "fat": 0.2 },
  "Broccoli": { "cal": 34, "pro": 2.8, "carb": 7, "fat": 0.4 },
  "Carrot": { "cal": 41, "pro": 0.9, "carb": 10, "fat": 0.2 },
  "Cauliflower": { "cal": 25, "pro": 1.9, "carb": 5, "fat": 0.3 },
  "Cabbage": { "cal": 25, "pro": 1.3, "carb": 6, "fat": 0.1 },
  "Spinach": { "cal": 23, "pro": 2.9, "carb": 3.6, "fat": 0.4 },
  "Ladyfinger": { "cal": 33, "pro": 2, "carb": 7, "fat": 0.2 },
  "Bitter Gourd": { "cal": 17, "pro": 1, "carb": 4, "fat": 0.2 },
  "Bottle Gourd": { "cal": 14, "pro": 0.6, "carb": 3.4, "fat": 0.1 },
  "Pumpkin": { "cal": 26, "pro": 1, "carb": 7, "fat": 0.1 },
  "Beetroot": { "cal": 43, "pro": 1.6, "carb": 10, "fat": 0.2 },
  "Milk": { "cal": 103, "pro": 8, "carb": 12, "fat": 2.4 },
  "Cheese": { "cal": 402, "pro": 25, "carb": 1.3, "fat": 33 },
  "Curd": { "cal": 98, "pro": 11, "carb": 4.7, "fat": 4.3 },
  "Paneer": { "cal": 265, "pro": 18, "carb": 6, "fat": 20 },
  "Butter": { "cal": 720, "pro": 0.1, "carb": 0, "fat": 80 },
  "Ghee": { "cal": 900, "pro": 0, "carb": 0, "fat": 100 },
  "Egg (boiled)": { "cal": 77, "pro": 6.3, "carb": 0.6, "fat": 5.3 },
  "Egg Omelette": { "cal": 154, "pro": 11, "carb": 1.6, "fat": 12 },
  "Cashews": { "cal": 553, "pro": 18, "carb": 30, "fat": 44 },
  "Walnuts": { "cal": 654, "pro": 15, "carb": 14, "fat": 65 },
  "Peanuts": { "cal": 567, "pro": 25, "carb": 16, "fat": 49 },
  "Pistachios": { "cal": 562, "pro": 20, "carb": 28, "fat": 45 },
  "Sunflower Seeds": { "cal": 584, "pro": 21, "carb": 20, "fat": 51 },
  "Flaxseed": { "cal": 534, "pro": 18, "carb": 29, "fat": 42 },
  "Chia Seeds": { "cal": 486, "pro": 17, "carb": 42, "fat": 31 },
  "Rice": { "cal": 130, "pro": 2.7, "carb": 28, "fat": 0.3 },
  "Brown Rice": { "cal": 112, "pro": 2.6, "carb": 23, "fat": 0.9 },
  "Chapati": { "cal": 104, "pro": 3, "carb": 18, "fat": 1.5 },
  "Paratha": { "cal": 260, "pro": 5, "carb": 36, "fat": 11 },
  "Poori": { "cal": 101, "pro": 2, "carb": 11, "fat": 6 },
  "Oats": { "cal": 389, "pro": 17, "carb": 66, "fat": 7 },
  "Cornflakes": { "cal": 357, "pro": 8, "carb": 84, "fat": 0.4 },
  "Whole Wheat": { "cal": 340, "pro": 13, "carb": 72, "fat": 2.5 },
  "Whole Grain Cereal": { "cal": 350, "pro": 8, "carb": 70, "fat": 3 },
  "Chicken(cooked)": { "cal": 239, "pro": 27.3, "carb": 0, "fat": 13.6 },
  "Chicken(raw)": { "cal": 120, "pro": 26, "carb": 0, "fat": 2 },
  "Mutton": { "cal": 280, "pro": 33, "carb": 0.1, "fat": 22 },
  "Fish": { "cal": 82, "pro": 18, "carb": 0, "fat": 1 },
  "Idli": { "cal": 39, "pro": 1.6, "carb": 8, "fat": 0.2 },
  "Dosa": { "cal": 133, "pro": 2.7, "carb": 17, "fat": 5 },
  "Upma": { "cal": 132, "pro": 3.1, "carb": 23, "fat": 3.7 },
  "Poha": { "cal": 130, "pro": 2.3, "carb": 27, "fat": 1.2 },
  "Biryani": { "cal": 292, "pro": 6, "carb": 38, "fat": 12 },
  "Pulao": { "cal": 250, "pro": 5, "carb": 35, "fat": 9 },
  "Dal": { "cal": 116, "pro": 9, "carb": 20, "fat": 0.4 },
  "Rajma": { "cal": 127, "pro": 9, "carb": 23, "fat": 0.5 },
  "Chole": { "cal": 164, "pro": 9, "carb": 27, "fat": 2.6 },
  "Samosa": { "cal": 262, "pro": 4, "carb": 31, "fat": 14 },
  "Pakora": { "cal": 310, "pro": 9, "carb": 25, "fat": 20 },
  "French Fries": { "cal": 312, "pro": 3.4, "carb": 41, "fat": 15 },
  "Burger": { "cal": 295, "pro": 17, "carb": 30, "fat": 13 },
  "Pizza": { "cal": 285, "pro": 12, "carb": 36, "fat": 10 },
  "Sandwich": { "cal": 250, "pro": 10, "carb": 30, "fat": 9 },
  "Pasta": { "cal": 131, "pro": 5, "carb": 25, "fat": 1.1 },
  "Maggi": { "cal": 350, "pro": 7, "carb": 50, "fat": 13 },
  "Pav Bhaji": { "cal": 400, "pro": 10, "carb": 45, "fat": 20 },
  "Bhel Puri": { "cal": 300, "pro": 7, "carb": 45, "fat": 10 },
  "Pani Puri": { "cal": 330, "pro": 6, "carb": 50, "fat": 12 },
  "Ice Cream": { "cal": 207, "pro": 3.5, "carb": 24, "fat": 11 },
  "Chocolate": { "cal": 546, "pro": 7.8, "carb": 61, "fat": 31 },
  "Laddu": { "cal": 160, "pro": 3, "carb": 22, "fat": 7 },
  "Jalebi": { "cal": 310, "pro": 2, "carb": 74, "fat": 0.1 },
  "Gulab Jamun": { "cal": 150, "pro": 2.5, "carb": 25, "fat": 5 },
  "Kheer": { "cal": 250, "pro": 7, "carb": 40, "fat": 7 },
  "Halwa": { "cal": 320, "pro": 5, "carb": 50, "fat": 12 },
  "Cake": { "cal": 235, "pro": 3, "carb": 30, "fat": 11 },
  "Biscuits": { "cal": 480, "pro": 6, "carb": 72, "fat": 20 },
  "Popcorn": { "cal": 387, "pro": 13, "carb": 78, "fat": 4 },
  "Soup": { "cal": 150, "pro": 5, "carb": 20, "fat": 6 },
  "Salad": { "cal": 80, "pro": 2, "carb": 15, "fat": 2 },
  "Sprouts": { "cal": 100, "pro": 8, "carb": 20, "fat": 1 },
  "Mushroom": { "cal": 22, "pro": 3.1, "carb": 3.3, "fat": 0.3 },
  "Tomatoes": { "cal": 18, "pro": 0.9, "carb": 4, "fat": 0.2 },
  "Onion": { "cal": 40, "pro": 1.1, "carb": 9, "fat": 0.1 },
  "Cucumber": { "cal": 16, "pro": 0.7, "carb": 4, "fat": 0.1 },
  "Green Peas": { "cal": 81, "pro": 5, "carb": 14, "fat": 0.4 },
  "Potato": { "cal": 77, "pro": 2, "carb": 17, "fat": 0.1 },
  "Sweet Potato": { "cal": 86, "pro": 1.6, "carb": 20, "fat": 0.1 },
  "Brinjal": { "cal": 25, "pro": 1, "carb": 6, "fat": 0.2 },
  "Drumstick": { "cal": 37, "pro": 2.1, "carb": 9, "fat": 0.2 },
  "Beef": { "cal": 250, "pro": 26, "carb": 0, "fat": 15 }
};

let currentMeal = "Breakfast";
let meals = { Breakfast:[], Lunch:[], Snacks:[], Dinner:[] };
let charts = { Breakfast:null, Lunch:null, Snacks:null, Dinner:null, Total:null };
function getTargets(){
  const age = +document.getElementById("age").value;
  const sex = document.getElementById("sex").value;
  const weight = +document.getElementById("weight").value;
  const height = +document.getElementById("height").value;
  let calories = sex==="male" ? 10*weight + 6.25*height - 5*age + 5 : 10*weight + 6.25*height - 5*age -161;
  return {cal:calories, pro:weight*0.8, fat:(0.3*calories)/9, carb:(calories-(weight*0.8*4+(0.3*calories)))/4};
}

function calculateBMR(){
  const t=getTargets();
  document.getElementById("bmrText").innerHTML = 
    `BMR ≈ ${Math.round(t.cal)} kcal | Protein ~${Math.round(t.pro)}g, Carbs ~${Math.round(t.carb)}g, Fat ~${Math.round(t.fat)}g`;
  updateSummary();
}
function renderList(cat="All"){
  const listDiv=document.getElementById("foodList");
  listDiv.innerHTML="";
  let items=cat==="All"?Object.values(categories).flat():categories[cat];
  items.forEach(n=>{
    const el=document.createElement("div");
    el.className="item";
    el.innerHTML=`<span>${n}</span><span style="color:var(--muted)">+</span>`;
    el.onclick=()=>addFood(n);
    listDiv.appendChild(el);
  });
}
function addFood(name){
  let f=meals[currentMeal].find(x=>x.name===name);
  if(f) f.qty++; else meals[currentMeal].push({name,qty:1});
  updateSelectedList();
}

function updateSelectedList(){
  const div=document.getElementById("selectedFoods");
  div.innerHTML="";
  meals[currentMeal].forEach((f,i)=>{
    div.innerHTML+=`${f.name} × <input type="number" value="${f.qty}" min="0" onchange="updateQty('${currentMeal}',${i},this.value)"> <button onclick="removeFood(${i})">Remove</button><br>`;
  });
  updateSummary();
}

function updateQty(meal,i,val){ meals[meal][i].qty=+val; updateSummary(); }
function removeFood(i){ meals[currentMeal].splice(i,1); updateSelectedList(); }
function removeAll(){ meals[currentMeal]=[]; updateSelectedList(); }
function resetAll(){ for(let m in meals) meals[m]=[]; updateSelectedList(); updateSummary(); }

function selectMeal(m,e){
  currentMeal=m;
  document.querySelectorAll(".meal-selector button").forEach(b=>b.classList.remove("active"));
  e.target.classList.add("active");
  updateSelectedList();
}
function updateChart(meal,s){
  const ctx=document.getElementById("chart"+meal).getContext("2d");
  if(charts[meal]) charts[meal].destroy();
  charts[meal]=new Chart(ctx,{
    type:"bar",
    data:{
      labels:["Protein","Carbs","Fat","Calories"],
      datasets:[{
        label:meal,
        data:[s.pro,s.carb,s.fat,s.cal],
        backgroundColor:[
          'rgba(255,99,132,0.7)',
          'rgba(54,162,235,0.7)',
          'rgba(255,206,86,0.7)',
          'rgba(43,138,62,0.7)'
        ]
      }]
    },
    options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
  });
}
function updateProgress(total) {
  const targets = getTargets();
  const updateBar = (id, value, target, labelName) => {
    const percent = (value / target) * 100;
    const bar = document.getElementById(id);
    bar.style.width = Math.min(100, percent) + "%";
    bar.querySelector(".bar-label").textContent = `${Math.round(value)}/${Math.round(target)}`;
    bar.style.background = "";
    const oldWarn = bar.parentElement.querySelector(".warn");
    if (oldWarn) oldWarn.remove();
    if (percent > 100) {
      bar.style.background = "rgba(220, 53, 69, 0.8)";
      const warn = document.createElement("div");
      warn.className = "warn";
      warn.style.color = "red";
      warn.style.fontSize = "12px";
      warn.textContent = `${labelName} target exceeded!`;
      bar.parentElement.appendChild(warn);
    }
  };
  updateBar("progCal", total.cal, targets.cal, "Calories");
  updateBar("progPro", total.pro, targets.pro, "Protein");
  updateBar("progCarb", total.carb, targets.carb, "Carbs");
  updateBar("progFat", total.fat, targets.fat, "Fat");
}
function calculateActivities(calories, weight){
  const metWalk=3.5, metJog=7, metRun=10;
  const calc=(met)=>{
    const calPerMin=(met*weight*3.5)/200;
    const time=calories/calPerMin;
    const speed=(met===3.5?5:met===7?8:10)/60;
    const dist=time*speed;
    return {time:Math.round(time), dist:dist.toFixed(2)};
  };
  return {walk:calc(metWalk), jog:calc(metJog), run:calc(metRun)};
}

let burnCharts={distance:null,time:null};
function updateBurnCharts(act){
  const ctxDist=document.getElementById("distanceChart").getContext("2d");
  const ctxTime=document.getElementById("timeChart").getContext("2d");
  if(burnCharts.distance) burnCharts.distance.destroy();
  if(burnCharts.time) burnCharts.time.destroy();
  burnCharts.distance=new Chart(ctxDist,{type:"bar",data:{labels:["Walk","Jog","Run"],datasets:[{label:"Distance (km)", data:[act.walk.dist, act.jog.dist, act.run.dist], backgroundColor:['#2b8a3e','#ff9900','#d6336c']}]}, options:{responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}}}});
  burnCharts.time=new Chart(ctxTime,{type:"bar",data:{labels:["Walk","Jog","Run"],datasets:[{label:"Time (min)", data:[act.walk.time, act.jog.time, act.run.time], backgroundColor:['#2b8a3e','#ff9900','#d6336c']}]}, options:{responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}}}});
}
function updateSummary(){
  const summaries=document.getElementById("mealSummaries");
  summaries.innerHTML="";
  let total={cal:0,pro:0,carb:0,fat:0};
  for(const [m,list] of Object.entries(meals)){
    let s={cal:0,pro:0,carb:0,fat:0};
    list.forEach(f=>{
      const n=nutrition[f.name];
      if(n){
        s.cal+=n.cal*f.qty;
        s.pro+=n.pro*f.qty;
        s.carb+=n.carb*f.qty;
        s.fat+=n.fat*f.qty;
      }
    });
    total.cal+=s.cal; total.pro+=s.pro; total.carb+=s.carb; total.fat+=s.fat;
    updateChart(m,s);
    summaries.innerHTML+=`<div class="meal-summary"><b>${m}</b>: ${s.cal} kcal, P:${s.pro}g, C:${s.carb}g, F:${s.fat}g</div>`;
  }
  updateChart("Total",total);
  updateProgress(total);
  const weight=+document.getElementById("weight").value;
  const act=calculateActivities(total.cal, weight);
  updateBurnCharts(act);
  document.getElementById("burnSummary").innerHTML=`To burn <b>${total.cal} kcal</b>:<br>Walk ${act.walk.dist} km (~${act.walk.time} min),<br>Jog ${act.jog.dist} km (~${act.jog.time} min),<br>Run ${act.run.dist} km (~${act.run.time} min)`;
}
document.querySelectorAll(".cat").forEach(el=>{
  el.onclick=()=>{
    document.querySelectorAll(".cat").forEach(c=>c.classList.remove("active"));
    el.classList.add("active");
    renderList(el.dataset.cat);
  };
});
renderList();
calculateBMR();
</script>
    
    <div style="text-align:center; margin:30px 0;">
      <button onclick="window.location.href='dashboard.php'" 
              style="padding:10px 20px; border:none; border-radius:8px; background:var(--accent); color:#fff; font-size:16px; cursor:pointer;">
        Go to Dashboard
      </button>
    </div>
</body>
</html>

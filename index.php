<?php
session_start();
if (isset($_SESSION['user'])) {
  // if already logged in, send to dashboard
  header("Location: dashboard.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Food Calorie Predictor</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #fbc2eb, #a6c1ee);
      height: 100vh;
      display: flex;
      flex-direction: column;
    }
    
    .navbar {
      display: flex;
      justify-content: flex-end;
      padding: 20px 40px;
    }
    .navbar a {
      margin-left: 15px;
      padding: 10px 20px;
      background: #6a5acd;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 500;
      transition: 0.3s;
    }
    .navbar a:hover {
      background: #483d8b;
    }

    
    .hero {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
    .hero h1 {
      font-size: 3rem;
      color: #333;
      margin-bottom: 15px;
    }
    .hero p {
      font-size: 1.2rem;
      color: #444;
      font-style: italic;
      max-width: 600px;
    }
  </style>
</head>
<body>
  
  <div class="navbar">
    <a href="login1.html">Login</a>
  </div>

  
  <div class="hero">
    <h1>Food Calorie Predictor</h1>
    <p>"Take control of your health – one meal, one calorie, one step at a time."</p>
  </div>
</body>
</html>
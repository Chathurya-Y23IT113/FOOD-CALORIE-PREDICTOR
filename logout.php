<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logged Out</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
    }
    .box {
      background: #ffffffcc;
      padding: 40px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      animation: fadeIn 1s ease-in-out;
    }
    h2 {
      color: #444;
      font-size: 24px;
      margin-bottom: 15px;
      animation: slideDown 1s ease;
    }
    p {
      color: #666;
      font-size: 16px;
      margin-bottom: 25px;
    }
    button {
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      background: #6a11cb;
      background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      transition: transform 0.2s ease, background 0.3s ease;
    }
    button:hover {
      transform: scale(1.05);
      background: linear-gradient(90deg, #2575fc 0%, #6a11cb 100%);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.9); }
      to { opacity: 1; transform: scale(1); }
    }
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Thank You!</h2>
    <p>You have been logged out successfully.<br>Have a wonderful day ahead</p>
    <button onclick="window.location.href='login1.html'">Go to Login Page</button>
  </div>
</body>
</html>
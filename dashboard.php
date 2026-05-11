<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login1.html");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard</title>

<style>

body{
margin:0;
padding:0;
font-family:Arial, sans-serif;
background:linear-gradient(to right,#fddb92,#d1fdff);
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
}

.container{
width:500px;
background:#fff;
padding:35px;
border-radius:18px;
text-align:center;
box-shadow:0 4px 12px rgba(0,0,0,0.2);
}

h1{
color:#333;
margin-bottom:25px;
}

.info{
font-size:18px;
margin:12px 0;
color:#444;
}

.info b{
color:#222;
}

.btn-group{
margin-top:30px;
display:flex;
justify-content:center;
gap:15px;
flex-wrap:wrap;
}

.btn{
padding:12px 22px;
border:none;
border-radius:10px;
font-size:16px;
cursor:pointer;
text-decoration:none;
color:white;
transition:0.3s;
}

.explore{
background:#28a745;
}

.explore:hover{
background:#218838;
}

.logout{
background:#dc3545;
}

.logout:hover{
background:#c82333;
}

.profile{
width:100px;
height:100px;
border-radius:50%;
background:#e9ecef;
display:flex;
justify-content:center;
align-items:center;
font-size:40px;
font-weight:bold;
color:#555;
margin:0 auto 20px;
}

</style>
</head>

<body>

<div class="container">

<div class="profile">
<?php echo strtoupper(substr($user['fullname'],0,1)); ?>
</div>

<h1>Welcome, <?php echo $user['fullname']; ?>!</h1>

<div class="info">
<b>Username:</b> <?php echo $user['username']; ?>
</div>

<div class="info">
<b>Email:</b> <?php echo $user['email']; ?>
</div>

<div class="info">
<b>Age:</b> <?php echo $user['age']; ?>
</div>

<div class="info">
<b>Sex:</b> <?php echo $user['sex']; ?>
</div>

<div class="info">
<b>Height:</b> <?php echo $user['height_cm']; ?> cm
</div>

<div class="info">
<b>Weight:</b> <?php echo $user['weight_kg']; ?> kg
</div>

<div class="btn-group">

<a href="foodlist.php" class="btn explore">
Explore Foods
</a>

<a href="logout.php" class="btn logout">
Logout
</a>

</div>

</div>

</body>
</html>
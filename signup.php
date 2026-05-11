<?php
session_start();
include 'db.php';

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$username = $_POST['username'];

$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$age = $_POST['age'];
$sex = $_POST['sex'];
$height_cm = $_POST['height_cm'];
$weight_kg = $_POST['weight_kg'];

$sql = "INSERT INTO users
(fullname,email,username,password,age,sex,height_cm,weight_kg)

VALUES

('$fullname','$email','$username','$password','$age','$sex','$height_cm','$weight_kg')";

if($conn->query($sql)==TRUE){

$_SESSION['user'] = [
'fullname'=>$fullname,
'email'=>$email,
'username'=>$username,
'age'=>$age,
'sex'=>$sex,
'height_cm'=>$height_cm,
'weight_kg'=>$weight_kg
];

header("Location: dashboard.php");
exit();

}else{
echo "Error";
}
?>
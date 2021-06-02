<?php 
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "socialmedia4";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("connection failed: " . $conn->connect_error);
}

$email = $_POST["email"];
$pwd = $_POST["pwd"];

$query = "SELECT email FROM accounts WHERE email='".$email."' AND pwd='".$pwd."';";
$result = $conn->query($query);

if($result){
    $_SESSION["email"] = $email;      
    header('Location: /socialmedia/home.php');

}
else{
   
}

?>
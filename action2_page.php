<?php  
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
$username = $_POST["username"];
echo $email;
echo $pwd;
echo $username;
$sql = "INSERT INTO accounts (email, pwd, username)
        VALUES ('".$email."','".$pwd."', '".$username."')";
$conn->query($sql);
$result  = "SELECT accountID FROM accounts WHERE email='".$email."' ";
$results = $conn->query($result);
if(mysqli_num_rows($results)){
    while($id = $results->fetch_assoc()){
        echo $id["accountID"];
        $set = "SET GLOBAL FOREIGN_KEY_CHECKS=0";
        $conn->query($set);
        $update = "INSERT INTO follows (accountID,following,email)
        VALUES ('".$id["accountID"]."','".$id["accountID"]."','".$email."')";
        $conn->query($update);
        echo $conn->error;
    }
}
?>
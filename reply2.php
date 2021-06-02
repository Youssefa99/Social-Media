<?php
session_start();
$email = $_SESSION["email"];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "socialmedia4";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("connection failed: " . $conn->connect_error);
}
$tweetID = $_POST['tweetID'];
$reply = $_POST['tweet'];
$sql= "SELECT accountID FROM accounts WHERE email='".$email."'";
$result = $conn->query($sql);
if($result){
    $row = $result->fetch_assoc();
    $accountID = $row['accountID'];
    $sql2 = "INSERT INTO replies (email, accountID, tweet, org_tweetID) VALUES ('".$email."', '".$accountID."', '".$reply."', '".$tweetID."')";
    $conn->query($sql2);
}
?>
<div class="reply">
                <div class="email">
                <h2> <?php echo $email; ?> </h2>
                </div>
                <div class="tweet">
               <h3> <?php echo $reply; ?> </h3>
                </div>
            </div>

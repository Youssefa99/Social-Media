<?php
session_start();
$newemail = $_SESSION["email"];
$lastID = $_SESSION["LastID"];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "socialmedia4";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("connection failed: " . $conn->connect_error);
}

$tweetID = $_POST["id"];
$sql = "SELECT email,accountID,tweet
        FROM tweets
        WHERE tweetID='".$tweetID."'";
$result = $conn->query($sql);
$sql2 = "SELECT accountID
         FROM accounts
         WHERE email='".$newemail."'";
$result2 = $conn->query($sql2);
if($result){
    $tweet = $result->fetch_assoc();
    if($result2){
        $account = $result2->fetch_assoc();
        $sql3 = "INSERT INTO shares (tweetID,email,accountID,org_ID,tweet,new_email) 
        VALUES ('".$tweetID."','".$tweet["email"]."','".$account["accountID"]."','".$tweet["accountID"]."','".$tweet["tweet"]."','".$newemail."')"; 
        $result3 = $conn->query($sql3);    
}
}

?>
<label id="sharescount_<?php echo $tweetID;?>"><?php $ShareCount = "SELECT COUNT(tweetID) 
                        AS total
                        FROM shares
                        WHERE tweetID='".$tweetID."'";
                        $cntResult = $conn->query($ShareCount);
                        $Scount = $cntResult->fetch_assoc();
                        echo $Scount["total"];?> Shares
                        </label>
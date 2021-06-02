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
$like = null;
$tweetID = $_POST["id"];
$sql = "SELECT accountID FROM accounts WHERE email='".$email."'";
$result = $conn->query($sql);
if($result){
    $account = $result->fetch_assoc();
    $check = "SELECT accountID, tweetID FROM likes WHERE accountID='".$account["accountID"]."' AND tweetID='".$tweetID."'";
    $check2 = $conn->query($check);
    if(mysqli_num_rows($check2) > 0){
        $delete = "DELETE FROM likes WHERE accountID='".$account["accountID"]."' AND tweetID='".$tweetID."'";
        $conn->query($delete);
        $ShareCount = "SELECT COUNT(tweetID) 
                            AS total
                            FROM likes
                            WHERE tweetID='".$tweetID."'";
                            $cntResult = $conn->query($ShareCount);
                            $Scount = $cntResult->fetch_assoc();
                            $total_likes = $Scount["total"] . " likes";
                            $like = 0;
                            $response = array('total_likes'=>$total_likes,'like'=>$like);
                            
                        header("Content-Type: application/json");
                            echo json_encode($response);
                       
}
else{
    $sql2 = "INSERT INTO likes (accountID, tweetID) VALUES('".$account["accountID"]."','".$tweetID."')";
    $conn->query($sql2);
     $ShareCount = "SELECT COUNT(tweetID) 
                        AS total
                        FROM likes
                        WHERE tweetID='".$tweetID."'";
                        $cntResult = $conn->query($ShareCount);
                        $Scount = $cntResult->fetch_assoc();
                        $total_likes = $Scount["total"] . " likes";
                        $like = 1;
                        $response = array('total_likes'=>$total_likes,'like'=>$like);
                        header("Content-Type: application/json");
                            echo json_encode($response);
                    }
}
?>

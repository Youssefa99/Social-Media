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
$tweet = $_POST["tweet"];
$id = "SELECT accountID, username FROM accounts WHERE email='".$email."'";
$result = $conn->query($id);
if($result)
$row = $result->fetch_assoc();
$accountID = $row["accountID"];
$username = $row["username"];
$sql = "INSERT INTO tweets (email,accountID,username,tweet) VALUES ('".$email."','".$accountID."','".$username."','".$tweet."')";
$conn->query($sql);
$sql2 = "SELECT tweetID FROM tweets WHERE email='".$email."' AND tweet='".$tweet."'";
$result2 = $conn->query($sql2);
if($result2){
    $tweet = $result2->fetch_assoc();

?>
<div class="post" id="post_<?php echo $tweet["tweetID"];?>" data-id="<?php 
                        echo $tweet["tweetID"];
                        $_SESSION["LastID"] = $tweet["tweetID"]; ?>">
    <a href="Sprofile.php?name=<?php echo $tweet2["email"];?>"><div class="email">
    <h2><?php $sql3 = "SELECT email, tweet, tweetID FROM tweets WHERE tweetID='".$tweet["tweetID"]."'";
              $result3 = $conn->query($sql3);
              if($result3){
                    $tweet2 = $result3->fetch_assoc();
                    echo $tweet2["email"];
              }
              $tweetID = $tweet2["tweetID"]; ?> </h2>
    </div></a>
    <a href="reply.php?id=<?php echo $follow["tweetID"];?>">
    <div class="tweet">
    <h3><?php echo $tweet2["tweet"]; ?></h3>
    </div> </a>
    <div class="interactions">
                        <div class="row">
                        <div class="col-2">
                        <button class="btn btn-primary" type="button" onclick="like(<?php echo $tweetID;?>)">Like</button>
                        <label id="count_<?php echo $tweet2["tweetID"];?>"><?php $ShareCount = "SELECT COUNT(tweetID) 
                        AS total
                        FROM likes
                        WHERE tweetID='".$tweet2["tweetID"]."'";
                        $cntResult = $conn->query($ShareCount);
                        $Scount = $cntResult->fetch_assoc();
                        echo $Scount["total"];?> likes</label>
                        </div>
                        <div class="col-2">
                        <button class="btn btn-primary" type="button" onclick="reply(<?php echo $tweetID;?>)">reply</button>
                        <label id="reply-count-<?php echo $tweet2["tweetID"]; ?>"><?php $replyCount = "SELECT COUNT(tweetID)
                        AS total
                        FROM replies
                        WHERE tweetID='".$tweet2["tweetID"]."'"; 
                        $repCount = $conn->query($replyCount);
                        $Rcount = $repCount->fetch_assoc();
                        echo $Rcount["total"];?> Replies </label>   
                    </div>
                        <div class="col-2">
                        <button class="btn btn-primary" type="button" onclick="share(<?php echo $tweetID;?>)">share</button>
                        <label id="sharescount_<?php echo $tweet2["tweetID"]?>"><?php $ShareCount = "SELECT COUNT(tweetID) 
                        AS total
                        FROM shares
                        WHERE tweetID='".$tweet2["tweetID"]."'";
                        $cntResult = $conn->query($ShareCount);
                        $Scount = $cntResult->fetch_assoc();
                        echo $Scount["total"];?> Shares
                        </label>
                        </div>
                        </div>
                        </div>
                                      
</div>
<?php
}
?>
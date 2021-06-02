<?php
session_start();
$email = $_SESSION["email"];
$lastID = $_SESSION["LastID"];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "socialmedia4";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("connection failed: " . $conn->connect_error);
}

?>
   <head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
         rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="home.css">
         <script type="text/javascript" src="interaction.js" ></script>
         <script src="jquery-3.5.1.js"></script>
</head>
        <div class="posts" id="posts">
            <?php 
            $sql = "SELECT accountID FROM accounts WHERE email='".$email."'";
            $result = $conn->query($sql);
            if(mysqli_num_rows($result) > 0){
                $row = $result->fetch_assoc();
                $home = "(SELECT tweets.email,tweets.tweet,tweets.tweetID,NULL AS new_email, NULL AS org_tweetID
                FROM tweets 
                where email IN (SELECT email 
                FROM follows 
                WHERE accountID='".$row["accountID"]."' AND tweets.tweetID > '".$lastID."') LIMIT 7) 
                UNION ALL (SELECT shares.email, shares.tweet, shares.tweetID, shares.new_email, NULL AS org_tweetID
                 FROM shares
                  WHERE new_email IN (SELECT email 
                                    FROM follows 
                                    WHERE accountID='".$row["accountID"]."' AND shares.tweetID > '".$lastID."') LIMIT 7)
                 UNION ALL (SELECT replies.email, replies.tweet, replies.replyID, NULL AS new_email, replies.org_tweetID
                 FROM replies
                  WHERE email IN (SELECT email 
                  FROM follows
                   WHERE accountID='".$row["accountID"]."' AND replies.org_tweetID > '".$lastID."') LIMIT 7)";
                 $result = $conn->query($home);
                if($result){
                    while($follow = $result->fetch_assoc()){
                        ?>
                        <div class="post">
                        <?php if($follow["new_email"]){
                         ?><a href="#"> <?php echo $follow["new_email"] ?> </a><?php  
                         echo " shared this post" . "<br>";
                      }
                      else if($follow["org_tweetID"]){
                          $get = "SELECT  username FROM tweets WHERE tweetID='".$follow["tweetID"]."'";
                          $user = $conn->query($get);
                          $username = $user->fetch_assoc();
                          echo "replying to " ?> 
                          <a href="#"> <?php echo $username["username"]; ?> </a><?php  
                          echo "<br>"; 
                      }?>
                        <a href="Sprofile.php?name=<?php echo $follow["email"];?>"><div class="email">
                      <h2>  <?php $_SESSION["LastID"] = $follow["tweetID"];
                      echo $follow["email"]; 
                      ?></h2>
                        </div></a>
                        <a href="reply.php?id=<?php echo $follow["tweetID"];?>">
                        <div class="tweet">
                        <h3> <?php   echo $follow["tweet"]; ?> </h3>
                        </div></a>
                    </div>
                    
                    <div class="interactions">
                        <div class="row">
                        <div class="col-2">
                        <button class="btn btn-primary" type="button" onclick="like(<?php echo $tweetID;?>)">Like</button>
                        <label id="count_<?php echo $follow["tweetID"];?>"><?php $ShareCount = "SELECT COUNT(tweetID) 
                        AS total
                        FROM likes
                        WHERE tweetID='".$follow["tweetID"]."'";
                        $cntResult = $conn->query($ShareCount);
                        $Scount = $cntResult->fetch_assoc();
                        echo $Scount["total"];?> likes</label>
                        </div>
                        <div class="col-2">
                        <button class="btn btn-primary" type="button" onclick="reply(<?php echo $tweetID;?>)">reply</button>
                        <label id="reply-count-<?php echo $follow["tweetID"]; ?>"><?php $replyCount = "SELECT COUNT(org_tweetID)
                        AS total
                        FROM replies
                        WHERE org_tweetID='".$follow["tweetID"]."'"; 
                        $repCount = $conn->query($replyCount);
                        $Rcount = $repCount->fetch_assoc();
                        echo $Rcount["total"];?> Replies </label>   
                    </div>
                        <div class="col-2">
                        <button class="btn btn-primary" type="button" onclick="share(<?php echo $tweetID;?>)">share</button>
                        <label id="sharescount_<?php echo $follow["tweetID"]?>"><?php $ShareCount = "SELECT COUNT(tweetID) 
                        AS total
                        FROM shares
                        WHERE tweetID='".$follow["tweetID"]."'";
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
                } 
            }     
            ?>
            </div>
        
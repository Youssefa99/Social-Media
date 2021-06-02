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
$qry = "SELECT accountID FROM accounts WHERE email='".$email."'";
$account = $conn->query($qry);
$account2 = $account->fetch_assoc();
$likeID = 0;
?>

<html>
    <head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
         rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="home.css">
         <script type="text/javascript" src="interaction.js" ></script>
         <script src="jquery-3.5.1.js"></script>
</head>
<header>
        <h1>Welcome to your homepage <?php
        echo $email;
        
        ?> </h1>
</header>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-lg-4">
    <table class="table table-striped">
        <tr>
            <td>
                <a href="home.php">Home</a>
            </td>
        </tr>        
        <tr>
            <td>
                <a href="profile.php">Profile</a>
            </td>
        </tr>
        <tr>
            <td>
                <a href="">settings</a>
            </td>
        </tr>
        <tr>
            <td>
                <button class="btn btn-default" type="button">Log out</button>
            </td>
        </tr>
    </table>
   <div>
       <label class="control-label">find your friends</label>
        <input type="email" name="search" id="search"  placeholder="enter email">
</div>
        <div>
        <button class="btn btn-primary" name="btnsearch" id="btnsearch" onclick="search()">Search</button>
</div>
        <div class="scrollbox">
<p style="color: lightGray;" id="search-results">you haven't searched for anyone</p>
</div>
</div>
    <br></br>
            <div class="col-md-8 col-lg-8" id="col">
                
                <div class="newTweet" id="newTweet">
                    <textarea type="text" id="newtweet" name="newtweet" placeholder="what's on your mind?" class="form-control"></textarea>
                        <div>                   
                    <button class="btn btn-primary" id="btnNewtweet" onclick="newTweet()">tweet</button>

                </div>
</div>
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
                WHERE accountID='".$row["accountID"]."') LIMIT 7) 
                UNION ALL (SELECT shares.email, shares.tweet, shares.tweetID, shares.new_email, NULL AS org_tweetID
                 FROM shares
                  WHERE new_email IN (SELECT email 
                                    FROM follows 
                                    WHERE accountID='".$row["accountID"]."') LIMIT 7)
                 UNION ALL (SELECT replies.email, replies.tweet, replies.replyID, NULL AS new_email, replies.org_tweetID
                 FROM replies
                  WHERE email IN (SELECT email 
                  FROM follows
                   WHERE accountID='".$row["accountID"]."') LIMIT 7)";
                $result = $conn->query($home); 
                if($result){
                    while($follow = $result->fetch_assoc()){
                        $tweetID = $follow["tweetID"];
                        $email = $follow["email"];
                        $tweet = $follow["tweet"];
                        ?>
                        <div class="post" id="post_<?php echo $follow["tweetID"];?>" data-id="<?php 
                        echo $follow["tweetID"];
                        $_SESSION["LastID"] = $follow["tweetID"]; ?>">
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
                      <h2>  <?php 
                      echo $follow["email"]; ?></h2>
                        </div></a>
                        <a href="reply.php?id=<?php echo $follow["tweetID"];?>">
                        <div class="tweet">
                        <h3> <?php   echo $follow["tweet"]; ?> </h3>
                        </div></a>
                        <div class="row interactions">
                        <div class="col-2 justify-content-center text-center">  
                        <?php
                        $likes = "SELECT accountID FROM likes WHERE accountID='".$row["accountID"]."' AND tweetID='".$follow["tweetID"]."'";
                        $result_likes = $conn->query($likes);
                        if(mysqli_num_rows($result_likes) > 0){
                            $color = 'crimson';
                        }
                        else{
                            $color = 'currentcolor';
                        }
            
                        ?>
                        <div class="like">
                        <a href="#" onclick="like('<?php echo $tweetID;?>')" class="like-button" ><svg
                        id="like-<?php echo $tweetID;?>" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="<?php echo $color; ?>" class="bi bi-heart-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                      </svg></a>
                        </div>
                        <label id="count_<?php echo $follow["tweetID"];?>" class="like-label"><?php $ShareCount = "SELECT COUNT(tweetID) 
                        AS total
                        FROM likes
                        WHERE tweetID='".$follow["tweetID"]."'";
                        $cntResult = $conn->query($ShareCount);
                        $Scount = $cntResult->fetch_assoc();
                        echo $Scount["total"];
                       ?> likes</label>
                        </div>
                        <div class="col-2 justify-content-center text-center">
                        <a href="#" onclick="reply(<?php echo $tweetID;?>)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16">
  <path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.499.499 0 0 0 .042-.028l3.984-2.933zM7.8 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z"/>
</svg></a>
                        <label id="reply-count-<?php echo $follow["tweetID"]; ?>"><?php $replyCount = "SELECT COUNT(org_tweetID)
                        AS total
                        FROM replies
                        WHERE org_tweetID='".$follow["tweetID"]."'"; 
                        $repCount = $conn->query($replyCount);
                        $Rcount = $repCount->fetch_assoc();
                        echo $Rcount["total"];?> Replies </label>   
                    </div>
                        <div class="col-2 justify-content-center text-center">
                        <a href="#" onclick="share(<?php echo $tweetID;?>)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-share" viewBox="0 0 16 16">
  <path d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/>
</svg></a>
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
                        <?php
                    }
                } 
            }     
            ?>
            </div>
            </div>
        </div>
    </div>
</body>
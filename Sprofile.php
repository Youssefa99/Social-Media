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
$Vemail = $_GET['name'];
$sql = "SELECT username, accountID FROM accounts WHERE email='".$Vemail."'";
$result = $conn->query($sql);
if($result){
    $account = $result -> fetch_assoc();
    $accountID = $account['accountID'];
    $username = $account['username'];
}
$sql2 = "SELECT username, accountID FROM accounts WHERE email='".$email."'";
$result2 = $conn->query($sql2);
if($result2){
    $org_account = $result2->fetch_assoc();
    $org_accountID = $org_account['accountID'];
    $org_username = $org_account['username'];
}
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
        <h1><?php echo $username;?>'s home page
        
         </h1>
</header>
<body>
    <div class="container">
        <div class="row">
            <div class="col-4">
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
<?php $sql3 = "SELECT COUNT(following) AS total FROM follows WHERE following='".$accountID."' ";
                         $result3 = $conn->query($sql3);
                         $followers = $result3 ->fetch_assoc();
                         $followers['total'] = $followers['total'] - 1; 
             ?>
    <br></br>
    <div class="col" id="col">
        <div class="row">
            <div class="col-2">
             <button class="btn btn-primary" type="button" onclick="follow('<?php echo $accountID;?>', '<?php echo $org_accountID;?>', '<?php echo $Vemail;?>','<?php echo $followers['total'];?>')">Follow</button>
            </div>
            <div class="col-2" id="followers-count">
            <label ><?php echo $followers['total'];
             ?> followers</label>
            </div>
            <div class="col-2">
            <label><?php $sql4 = "SELECT COUNT(accountID) AS total FROM follows WHERE accountID='".$accountID."' ";
                         $result4 = $conn->query($sql4);
                         $following = $result4 ->fetch_assoc();
                         $following['total'] = $following['total'] - 1;
                         echo $following['total'];
             ?> follows</label>
            </div>
        </div>
        <div class="posts" id="posts">
            <?php 
                $home = "(SELECT tweets.email,tweets.tweet,tweets.tweetID 
                FROM tweets 
                where email='".$Vemail."' LIMIT 7) 
                  UNION ALL
                  (SELECT shares.email, shares.tweet, shares.tweetID
                   FROM shares
                    WHERE new_email='".$Vemail."' LIMIT 7)";
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
                        <div class="email">
                      <h2>  <?php 
                      echo $follow["email"]; ?></h2>
                        </div>
                        <div class="tweet">
                        <h3> <?php   echo $follow["tweet"]; ?> </h3>
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
                        <label id="reply-count-<?php echo $follow["tweetID"]; ?>"><?php $replyCount = "SELECT COUNT(tweetID)
                        AS total
                        FROM replies
                        WHERE tweetID='".$follow["tweetID"]."'"; 
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
                 
            ?>
            </div>
            </div>
        </div>
    </div>
</body>
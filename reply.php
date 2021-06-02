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
$tweetID = $_GET['id'];
$sql = "SELECT email,tweet FROM tweets WHERE tweetID='".$tweetID."'";
$result = $conn->query($sql);
if(mysqli_num_rows($result)){
    $org_account = $result->fetch_assoc();
    $org_email = $org_account["email"];
    $org_tweet = $org_account["tweet"];
}
?>
<html>
    <head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
         rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="reply.css">
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
<br></br>
    <div class="col">
                <div class="org-tweet">
                    <div class="org-email pb-3">
                        <h2><?php echo $org_email; ?></h2>
                    </div>
                    <div class="org-tweet">
                        <h3><?php echo $org_tweet; ?></h3>
                    </div>
                </div>
                <div class="replies" id="replies"><?php
                $sql2 = "SELECT email,tweet,replyID FROM replies WHERE org_tweetID='".$tweetID."'";
                $result2 = $conn->query($sql2);
                if(mysqli_num_rows($result2) > 0){
                    while($reply = $result2->fetch_assoc()){
                        ?>
                        <div class="reply">
                            <div class="reply-email pb-2">
                                <h2><?php echo $reply["email"]; ?></h2>
                            </div>
                            <div class="reply-text pb-2">
                                <h3><?php echo $reply["tweet"]; ?></h3>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                </div>
                <div class="new-reply">
              <div class="col-sm-10">
                <textarea type="text" class="form-control" id="newReply" placeholder="Enter a reply" name="reply"></textarea>
              </div>
            </div>
            <button type="button" onclick="newReply(<?php echo $tweetID; ?>)" class="btn btn-primary" id="reply-btn">newReply</button>
                </div>
            </div>
        </div>
    </div>
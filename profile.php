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

?>

<html>
    <head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
         rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="home.css">
         <script src="profile_interaction.js"></script>
         <script src="jquery-3.5.1.js"></script>
</head>
<header>
        <h1>Welcome to your profile <?php
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
        <label>Find your Friends</label>
    <div>
    <input class="search" name="search" id="search" placeholder="enter your friends email">
    <div>
        <button class="btn btn-primary" name="btnsearch" id="btnsearch" onclick="search()">Search</button> 
    </div>
    </div>
    <div class="scrollbox">
<p style="color: lightGray;" id="search-results">you haven't searched for anyone</p>
</div>
    <br></br>
    </div>
            </div>
            <div class="col" id="col">
            <?php 
            $sql = "SELECT accountID FROM accounts WHERE email='".$email."'";
            $result = $conn->query($sql);
            if(mysqli_num_rows($result) > 0){
                $row = $result->fetch_assoc();
                $posts = "SELECT tweet, tweetID FROM tweets WHERE accountID='".$row["accountID"]."' LIMIT 7";
                $tweets = $conn->query($posts);
                if(mysqli_num_rows($tweets) > 0){
                    while($tweet = $tweets->fetch_assoc()){
                        ?>
                        <div class="post">
                        <div class="email">
                      <h2>  <?php    echo $email;
                      $_SESSION["LastID"] = $tweet["tweetID"]; ?></h2>
                        </div>
                        <div class="tweet">
                        <h3> <?php   echo $tweet["tweet"]; ?> </h3>
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
</body>
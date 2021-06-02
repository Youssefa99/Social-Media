<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "socialmedia4";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("connection failed: " . $conn->connect_error);
}

$search = $_GET["search"];
$sql = "SELECT email, accountID FROM accounts WHERE username='".$search."'";
$result = $conn->query($sql);
if(mysqli_num_rows($result) > 0){
    while($row = $result->fetch_assoc()){
        ?>
        <div id="search-results" data-id="<?php echo $row["accountID"];?>">
            <div class="email" style="height: 100px;color: black;">
          <a href="Sprofile.php?name=<?php echo $row["email"];?>"  style="display: block; height: 20px;"> <?php echo $row["email"]; ?> </a>
                
        </div>
    </div>
    <hr></hr>
    <?php
    }
}
?>
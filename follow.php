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
$following = $_POST['following'];
$accountID = $_POST['accountID'];
$Femail = $_POST['email'];
$total = $_POST['total'];
$sql = "SELECT * FROM follows where accountID='".$accountID."' AND following='".$following."'";
$result = $conn->query($sql);
if(mysqli_num_rows($result) > 0){
    $delete = "DELETE FROM follows WHERE accountID='".$accountID."' AND following='".$following."'";
    $del = $conn->query($delete);
    $total = $total - 1;
    ?>
    <label><?php echo $total;
             ?> followers</label>
<?php }
else{
    $follow = "INSERT INTO follows (accountID,following,email) VALUES ('".$accountID."','".$following."','".$Femail."')";
    $fol = $conn->query($follow);
    ?>
    
    <label><?php echo $total;
             ?> followers</label>
<?php }
?>
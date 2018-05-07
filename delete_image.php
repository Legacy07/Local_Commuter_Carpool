<?php 
//session_start();

//output buffering for cookies so it sends out headers before any code
ob_start();
//declaring variables
$uN = $_COOKIE['userName']; 


// Database Connection 
$conn = mysqli_connect("mysql.cms.gre.ac.uk","ai6935u","Mertmert2019","mdb_ai6935u");
if (mysqli_connect_errno($conn)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
//getting off the url to verify
$postID = mysqli_real_escape_string($conn, $_GET['id']);

$delete = null;

//update the data 
mysqli_query($conn, "UPDATE Post Set ImageType = '$delete', ImageName = '$delete', Image = '$delete' WHERE ID = '$postID'");
//$message = "Are you sure you want to delete the image?";
//echo "<script> location.href='http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/memberposts.php'; </script>";
header("Location:memberposts.php");


die();


?>
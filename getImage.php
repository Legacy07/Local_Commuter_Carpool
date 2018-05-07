<?php
// Database Connection 
$conn = mysqli_connect("mysql.cms.gre.ac.uk","ai6935u","Mertmert2019","mdb_ai6935u");
if (mysqli_connect_errno($conn)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
} 
//adapted to get the image to output in member posts and search from Mahtab's resources PHP 3 Image Uploading
//gets Image type and Image where post id is from the url
$query = 'SELECT ImageType,Image FROM Post WHERE ID="' . $_GET['ID'] . '"';
$result = mysqli_query($conn, $query);
//fetch assoicated column which is Image type and Image to output
$row = mysqli_fetch_assoc($result);
header('Content-Type: ' . $row['ImageType']);
echo $row['Image'];
?>
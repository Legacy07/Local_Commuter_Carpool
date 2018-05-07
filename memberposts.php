<?php 
// session starts
//session_start();
//output buffering for cookies so it sends out headers before any code
ob_start();
?>  

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">

    <?php
    //declaring variables
    $message = "";
//if the cookie is empty redirect to home page
if (empty($_COOKIE['userName'])){
    //echo "<script> location.href='http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/home.php'; </script>";
    header("Location:home.php");

}else {
    //get the cookie 
    $uN = $_COOKIE['userName']; 
}

// Database Connection 
$conn = mysqli_connect("mysql.cms.gre.ac.uk","ai6935u","Mertmert2019","mdb_ai6935u");
if (mysqli_connect_errno($conn)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
} 
//get the data from post if there are any associated with the username
$sql = mysqli_query($conn, "SELECT * From Post where Username = '$uN'");

//get the rows
$row = mysqli_fetch_assoc($sql);

    ?>
    <head>
        <!--     adapted to have a responsive design but not 100% of a mobile view from - https://webdesign.tutsplus.com/articles/quick-tip-dont-forget-the-viewport-meta-tag--webdesign-5972 -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- using the custom css -->    
        <link rel="stylesheet" href="style.css"/>
        <title>My posts</title>
    </head>
    <body>
        <!--navigation bar in lists-->
        <ul>
            <li><a href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/home.php">What is Carpool?</a></li>
            <li><a href="#news">Contact</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/logout.php">Logout</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/post.php">Post a Journey</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/search.php">Search</a></li>
        </ul>

        <!--            if there is no post then do not generate the table-->
        <?php if(empty($row['Username'])){ ?>
        <p class="tableHeader">No Data found. Post a Journey to view your posts.</p>
        <?php }else{ ?>
        <!-- table contains the post data -->
        <p class="tableHeader">You can edit or delete your posts</p>
        <div class="center">
            <!-- Adapted to generate the table from - A Beginner's Guide PHP and MySQL Web Development, pg. 467 - 468  -->
            <table id="table" >
                <!-- Display the column headings -->
                <tr>
                    <th class="list" width="40">Post#</th>
                    <th class="list" width="50">Purpose</th>
                    <th class="list" width="150">Starting Point</th>
                    <th class="list" width="150">Destination</th>
                    <th class="list" width="100">Date</th>
                    <th class="list" width="100">Car</th>
                    <th class="list" width="150">Cost Sharing</th>
                    <th class="list" width="400">Details</th>
                    <th class="list" width="400">Image</th>
                    <th class="list" width="50">&nbsp</th>
                    <th class="list" width="40">&nbsp</th>
                    <th class="list" width="40">&nbsp</th>

                </tr>

                <!-- inserting the data in rows -->
                <?php do {  ?>

                <tr>
                    <td align="center"><?php echo $row ['ID']; ?></td>
                    <td align="center"><?php echo $row ['Purpose']; ?></td>
                    <td ><?php echo $row ['Starting_Point']; ?></td>
                    <td ><?php echo $row ['Destination']; ?></td>
                    <td align="center"><?php echo $row ['Date']; ?></td>
                    <td align="center"><?php echo $row ['Car']; ?></td>
                    <td align="center"><?php echo $row ['Cost_Sharing']; ?></td>
                    <td ><?php echo $row ['Details']; ?></td>
                    <td ><?php echo '<img src="getImage.php?ID=' . $row['ID'] . '" name="' . $row['ImageName']  .' width= "50" height="70" " />  ';
                        ?> </td>
                    <!-- creating the dynamic page where its unique to each user because of their id and can edit or delete their posts by it -->
                    <td><a href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/edit_post.php?id=<?php echo $row ['ID']; ?>">Edit</a></td>
                    <td><a href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/edit_post.php?id=<?php echo $row ['ID']; ?>">Delete</a></td>
                    <td><a href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/delete_image.php?id=<?php echo $row ['ID']; ?>">Delete Image</a></td>

                </tr> 

                <?php } while ( $row = mysqli_fetch_assoc($sql) ); ?>
                <?php } ?>
            </table>

        </div>

    </body>
</html>
<?php 
// session starts
session_start(); 
//output buffering for cookies so it sends out headers before any code
ob_start();
?>  

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">

    <?php
    //declaring variables
    $message = "";

// Database Connection 
$conn = mysqli_connect("127.0.0.1","root","","mysql");
if (mysqli_connect_errno($conn)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
} 

    ?>
    <head>
        <!--        mobile view-->
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
            <!-- if the user is logged in show log out button -->
            <?php if (isset($_COOKIE['userName'])) { ?>
            <li style="float:right" ><a  href="logout.php">Logout</a></li>
            <li style="float:right" ><a  href="memberposts.php">My Posts</a></li>
            <li style="float:right" ><a  href="post.php">Post a Journey</a></li>
            <?php } else { ?>
            <li style="float:right" ><a  href="login.php">Login</a></li>
            <li style="float:right" ><a  href="registration.php">Register</a></li>

            <?php } ?>
        </ul>

        <!-- table contains the post data -->
        <!--        <p class="tableHeader">Posts</p>-->

        <div class="searchBox" >
            <form name="searchForm"action="search.php" method="post">
                <label for="start">Starting Point</label>
                <input type="text" id="start" name="startingP" value="<?php if (empty($_COOKIE["sP"])) {} else {echo $_COOKIE["sP"];} ?>" placeholder="e.g. Stratford">

                <label for="destination">Destination</label>
                <input type="text" id="destination" name="destination" value="<?php if (empty($_COOKIE["destination"])) {} else {echo $_COOKIE["destination"];} ?>"placeholder="e.g. Mile End">

                <label for="date">Select Date & Time</label>
                <input type="datetime-local" name="date" value="<?php if (empty($_COOKIE["date"])) {} else {echo $_COOKIE["date"];} ?>"><br>

                <strong>Filter by</strong>
                <div class="filterBox">
                    <input type="checkbox" name="excludeMember" value="excludeMember">  
                    <label for="excludeMember">Exclude members without images</label>

                </div>
                <br>
                <input type="submit" value="Search" name="searchButton">


            </form>

        </div>

        <?php
        //if register button is succesfully clicked then send data from textboxes into these variables
        if (isset($_POST['searchButton']))
        {   
            $sP   = $_POST['startingP'];
            $destination  = $_POST['destination'];
            $date  = $_POST['date'];

            //Cookies created to save last search fields
            //But it only works after the user redirects to a new page and comes back to it again. However when the user clicks on 'Search' the fields go blank. 
            //set cookie with 30 days expiry
            setcookie("sP", $sP, time() + (86400 * 30), "/");
            setcookie("destination", $destination, time() + (86400 * 30), "/");
            setcookie("date", $date, time() + (86400 * 30), "/");

            //            //if the checkbox isnt checked then search normally
            //            if(!isset($_POST['excludeMember'])){

            //selecting post data, it will output if fields are empty and the user doesnt need to enter the full location
            //for date, the time have the be pin point but the date is searched for the specific days
            $posts = mysqli_query($conn, "SELECT * FROM Post where Starting_Point LIKE '%{$sP}%' AND Destination LIKE '%{$destination}%' AND Date LIKE '%{$date}%'");
            //get the rows
            $row = mysqli_fetch_assoc($posts);

            //            }

            //            if there is filter which is to find posts with only images search posts with images
            //            else if ($_POST['excludeMember']) { 
            //                //checking to see for any images uploaded with posts
            //                $checkImage = mysqli_query($conn, "SELECT ImageName FROM Post where Starting_Point LIKE '%{$sP}%' AND Destination LIKE '%{$destination}%' AND Date LIKE '%{$date}%'");
            //
            //               // $count = mysqli_num_rows($checkImage);
            //
            //                //Do it in a way where i get the imagename and check if it equals to null
            //                //if there is no images with posts then dont show
            ////                if($count === null){
            ////                    echo "empty";
            ////
            ////                }
            //                //this doesnt work either for image filtering
            ////                $row = mysqli_fetch_assoc($checkImage); 
            ////                if ($row ['ImageName'] == null) {echo "error";}
            ////                
            ////                
            ////                //if there are images with posts, show
            ////                else{
            ////                    //selecting post data, it will output if fields are empty and the user doesnt need to enter the full location and exclude posts if there is no images uploaded with it
            ////                    $posts = mysqli_query($conn, "SELECT * FROM Post where Starting_Point LIKE '%{$sP}%' AND Destination LIKE '%{$destination}%' AND Date LIKE '%{$date}%'");
            ////                    //get the rows
            ////                    $row = mysqli_fetch_assoc($posts);               
            ////                }
            //
            //            } 

        ?>
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
                    <!--                    if the user isnt logged in then redirect to login page -->
                    <td><a  <?php if (isset($_COOKIE['userName'])) { ?> href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/post_more_information.php?id=<?php echo $row ['ID']; ?>" <?php } else{ ?>href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/login.php" <?php } ?> >More Information>></a></td>
                </tr> 

                <?php } while ( $row = mysqli_fetch_assoc($posts) ); ?>
            </table>

        </div>

        <?php  
        }
        //close connection
        mysqli_close($conn);

        ?>


    </body>
</html>
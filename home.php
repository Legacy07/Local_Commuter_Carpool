<?php   
//session starts
//session_start(); 

//output buffering for cookies so it sends out headers before any code
ob_start();
?>

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">
    <head>
        <!--     adapted to have a responsive design but not 100% of a mobile view from - https://webdesign.tutsplus.com/articles/quick-tip-dont-forget-the-viewport-meta-tag--webdesign-5972 -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- using the custom css -->    
        <link rel="stylesheet" href="home_style.css"/>
        <title>Home</title>
    </head>

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

    <body>
        <!-- header -->

        <ul>
            <!-- if the user is logged in show log out button -->
            <?php if (isset($_COOKIE['userName']) && !empty($_COOKIE['userName'])) { ?>
            <li><a href="search.php" title="Post">Search</a></li>  
            <li><a href="memberposts.php" title="Post">My Posts</a></li> 
            <li><a href="post.php" title="Post">Post a Journey</a></li>  
            <li><a href="logout.php" title="Post">Logout</a></li>  

            <?php } 
            //show the log in link
            else { ?>
            <li><a href="registration.php" title="Register">Register</a></li>
            <li><a href="login.php" title="Login">Login</a></li>
            <li><a href="search.php" title="Login">Search</a></li>
            <?php } ?> 
        </ul>

        <!-- <h1     class="logo"><img src="images/logo.png" alt="Royal Borough of Greenwich Logo" width="120" height="100">

</h1> -->

        <div class="leftDescription"><p> <strong>Carpool Commute</strong>
            <br><br>
            <strong>We are on a mission to</strong>
            improve the urban environment by enabling neighbours to commuter carpool and reduce the
            amount of cars on the roads, thereby reducing traffic congestion and air pollution!
            <br><br>
            <strong>Casual visitors </strong>to the site will be able to search through the posts to see
            if anyone faces a similar commute to theirs.
            <br><br>
            <strong>While  any visitor </strong>can search through the various
            posts, full details of other commuters and their journeys will only be available after
            registering to this web site.
            </p>

        </div>

    </body>
</html>

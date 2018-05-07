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

// Database Connection 
$conn = mysqli_connect("mysql.cms.gre.ac.uk","ai6935u","Mertmert2019","mdb_ai6935u");
if (mysqli_connect_errno($conn)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
} 
//if the cookie is empty redirect to home page
if (empty($_COOKIE['userName'])){
    //echo "<script> location.href='http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/home.php'; </script>";
    header("Location:home.php");
}else {
    //get the cookie 
    $uN = $_COOKIE['userName']; 
}

    ?>

    <head>
        <!--        mobile view-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- using the custom css -->    
        <link rel="stylesheet" href="style.css"/>
        <title>Post Journeys</title>
    </head>
    <body>
        <!--navigation bar in lists-->
        <ul>
            <li><a href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/home.php">What is Carpool?</a></li>
            <li><a href="#news">Contact</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/logout.php">Logout</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/memberposts.php">My Posts</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/search.php">Search</a></li>
        </ul>

        <p class="postHeader">Schedule your journey</p>
        <div>
            <!-- journey box -->
            <div class="postScheduleBox">
                <form name="phpForm" action="post.php" method="post" enctype="multipart/form-data">

                    <input type="radio" name="purpose" value="Obtain" checked> Obtain a lift
                    <input type="radio" name="purpose" value="Provide"> Provide a lift
                    <input type="radio" name="purpose" value="Both"> Both<br>

                    <label for="startingPoint">Starting Point*</label>
                    <input type="text" id="startingPoint" name="startingPoint" placeholder="e.g. North Greenwich" required>

                    <label for="destination">Destination*</label>
                    <input type="text" id="destination" name="destination" placeholder="e.g. Cutty Sark" required>

                    <label for="date">Date*</label><br>
                    <input type="datetime-local" id="date" name="date" required><br>

                    <label for="car">Car type/model</label>
                    <input type="text" id="car" name="car" placeholder="e.g. Audi" >

                    <label for="cost">How much are you willing to chip in? (in percentages)</label>
                    <input type="text" id="cost" name="cost" placeholder="e.g. 30% " >

                    <label for="details">Extra Details:</label>
                    <textarea rows="4" cols="44" name="details" maxlength="200" placeholder="Max Length: 200 words"></textarea><br>

                    <label for="imageFile">Select an image to upload:</label>
                    <input type="file" size="40" multiple="multiple" name="imageFile" id="imageFile"/><br><br>

                    <input type="submit" name="postButton" value="Post Journey">


                </form>


            </div>
            <?php   //if post journey button is succesfully clicked then send data from textboxes into these variables
            if (isset($_POST['postButton']))
            {   
                $purpose   = $_POST['purpose'];
                $sP   = $_POST['startingPoint'];
                $destination   = $_POST['destination'];
                $date   = $_POST['date'];
                $car   = $_POST['car'];
                $cost   = $_POST['cost'];
                $details   = $_POST['details'];
                //$uN = $_COOKIE['userName']; 

                //                validation works as it doesnt allow special characters and apostraphe but after it echo outs the error, it will keep showing the issue even if i input correctly. But if i refresh the page then i can perform this. 
                //And with apostraphes it doesnt work so i had to revert to basic regex to allow only letters but this includes other caharacters like apostraphe which causes problems in to not saving within database.
                //update: it works but this doesnt allow to update the post when everything is entered correctly for some reason

                //validation adapted from Mahtab resources PHP 3 MySQLi Mailing List and https://stackoverflow.com/questions/12778083/regex-with-space-and-letters-only 

                //                if ( !empty($car) || !empty ($details) ){
                //
                //                    //validation for special characters and numbers, but combinations with special characters and numbers are allowed and saved in database
                //                    if ( !preg_match("/^[a-zA-Z\s]*$/", $car) ){
                //                        echo "Special characters and numbers are not allowed, for example; <\!@~#/ 0-9> ";
                //                    }
                //                    //validation for special characters and numbers, but combinations with special characters and numbers are allowed and saved in database
                //                    else if ( !preg_match("/^[a-zA-Z\s]*$/", $details) ){
                //                        echo "Special characters and numbers are not allowed, for example; <\!@~#/ 0-9> ";
                //                    }
                //                }


                //validation for special characters and numbers, but combinations with special characters and numbers are allowed and saved in database
                if ( !preg_match("/^[a-zA-Z\s]*$/", $sP) ){
                    echo "Special characters and numbers are not allowed, for example; <\!@~#/ 0-9> ";
                }
                //validation for special characters and numbers, but combinations with special characters and numbers are allowed and saved in database
                else if ( !preg_match("/^[a-zA-Z\s]*$/", $destination) ){
                    echo "Special characters and numbers are not allowed, for example; <\!@~#/ 0-9> ";
                }

                //image upload and its validation adapted from Mahtab resources PHP 3 Image Uploading
                else{

                    //if no image is uploaded then publish the post and open my post page
                    if ( empty($_FILES['imageFile']['type']) ) {
                        //insert values into rows
                        $sql = mysqli_query($conn, "INSERT INTO Post (Username, Starting_Point, Destination, Date, Car, Cost_Sharing, Details, Purpose)
                            VALUES ('$uN', '$sP','$destination', '$date', '$car', '$cost', '$details', '$purpose')");
                        //echo "<script> location.href='http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/memberposts.php'; </script>";
                        header("Location:memberposts.php");
                        die();
                    }
                    // Validate uploaded image file
                    else if ( !preg_match( '/gif|png|x-png|jpeg/', $_FILES['imageFile']['type']) ) {
                        die('<p>Only browser compatible images allowed</p></body></html>');
                    }//Check for the size of the file 
                    else if ( $_FILES['imageFile']['size'] > 204800 ) {
                        die('<p>Sorry file too large</p></body></html>');
                    }
                    // Copy image file into a variable
                    else if ( !($handle = fopen ($_FILES['imageFile']['tmp_name'], "r")) ) {
                        die('<p>Error opening temp file</p></body></html>');
                    } else if ( !($image = fread ($handle, filesize($_FILES['imageFile']['tmp_name']))) ) {
                        die('<p>Error reading temp file</p></body></html>');
                    } else {
                        fclose ($handle);
                        // Commit image to the database
                        $image = mysqli_real_escape_string($conn, $image);
                        $imageName = $_FILES['imageFile']['name'];
                        $imageType = $_FILES['imageFile']['type'];

                        //insert values into rows
                        $sql = 'INSERT INTO Post (Username, Starting_Point, Destination, Date, Car, Cost_Sharing, Details, Purpose, ImageType, ImageName, Image) 
                    VALUES ("'. $uN .'", "'. $sP .'","'. $destination .'", "'. $date .'", "'. $car .'", "'. $cost .'", "'. $details .'", "'. $purpose .'","'. $imageType .'","'. $imageName .'","'. $image .'" )';
                        //it gets the id of the inserted rows
                        //$id = mysqli_insert_id($conn);
                        //  $count = mysqli_num_rows($postID);

                        //                    //insert image data. 
                        //                    $imageQuery = 'INSERT INTO Images (PostID,Type,Name,Image) VALUES ("'. $id .'","' . $_FILES['imageFile']['type'] . '","' . $_FILES['imageFile']['name']  . '","' . $image . '")';

                        //if the query didnt work output
                        if ( !(mysqli_query($conn, $sql)) ) {
                            die('<p>Error writing image to database</p></body></html>');
                        } else {
                            //echo "<script> location.href='http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/memberposts.php'; </script>";
                            header("Location:memberposts.php");

                        }}
                }
            }

            //close connection
            mysqli_close($conn);

            ?>

        </div>
    </body>
</html>

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
$uN = $_COOKIE['userName']; 


// Database Connection 
$conn = mysqli_connect("mysql.cms.gre.ac.uk","ai6935u","Mertmert2019","mdb_ai6935u");
if (mysqli_connect_errno($conn)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
//get the data from post if there are any associated with the username
$sql = mysqli_query($conn, "SELECT * From Post where Username = '$uN'");
//get the amount of rows 
$count = mysqli_num_rows($sql);
//get the first row
$idRow = mysqli_fetch_assoc($sql);

//getting off the url to verify
$postID = mysqli_real_escape_string($conn, $_GET['id']);
//verify the post with username
$search = mysqli_query($conn, "SELECT ID FROM Post WHERE Username='$uN' AND ID = '$postID'") or die(mysqli_error($conn)); 
//find the number of rows where username and post id matches
$match  = mysqli_num_rows($search);

//if there is a match then populate the fields with the data from db
if($match >= 1){
    // find the post data and populate the data in text boxes below in html
    $populate= mysqli_query($conn, "SELECT Starting_Point, Destination, Date, Car, Cost_Sharing, Details, Purpose FROM Post Where ID = '$postID'") or die(mysqli_error($conn));
    //find the rows thats associated to the columns and output the row associated to it 
    $row = mysqli_fetch_array($populate, MYSQLI_ASSOC);
    //to find purpose where post id is from the url
    $purpose = mysqli_query($conn, "SELECT Purpose FROM Post WHERE ID='$postID'") or die(mysqli_error($conn)); 
    $fetch = mysqli_fetch_assoc($purpose);



}else{
    // Post cant be found.
    $output = " Post cant be found!";
    $header = "Error occured!";
}

    ?>

    <head>
        <!--        mobile view-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- using the custom css -->    
        <link rel="stylesheet" href="style.css"/>
        <title>Edit Post</title>
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

        <p class="postHeader">Edit My Post</p>
        <div>
            <!-- journey box -->
            <div class="postScheduleBox">
                <!--  <form name="phpForm" action="edit_post.php?id=<?php echo $idRow ['ID']; ?>" method="post" enctype="multipart/form-data"> -->
                <form name="phpForm" action="edit_post.php?id=<?php echo $postID; ?>" method="post" enctype="multipart/form-data">
                    <!-- Weren't able to output the selection of radio button so its already checked when the page is loaded-->
                    <input type="radio" name="purpose" value="Obtain" <?php if($fetch=="Obtain") echo "checked" ?> checked required> Obtain a lift
                    <input type="radio" name="purpose" value="Provide"> <?php if($fetch=="Provide") echo "checked" ?> Provide a lift
                    <input type="radio" name="purpose" value="Both"><?php if($fetch=="Both") echo "checked" ?> Both<br>

                    <label for="startingPoint">Starting Point*</label>
                    <input type="text" id="startingPoint" name="startingPoint" value="<?php echo $row['Starting_Point'] ;?>" placeholder="e.g. North Greenwich" required>

                    <label for="destination">Destination*</label>
                    <input type="text" id="destination" name="destination" value="<?php echo $row['Destination'];?>" placeholder="e.g. Cutty Sark" required>

                    <label for="date">Date*</label><br>
                    <input type="datetime-local" id="date" value="<?php echo $row['Date'] ;?>" name="date" required><br>

                    <label for="car">Car type/model</label>
                    <input type="text" id="car" name="car" value="<?php echo $row['Car'] ;?>" placeholder="e.g. Audi" >

                    <label for="cost">How much are you willing to chip in? (in percentages)</label>
                    <input type="text" id="cost" name="cost" value="<?php echo $row['Cost_Sharing'] ;?>" placeholder="e.g. 30% " >

                    <label for="details">Extra Details:</label>
                    <textarea rows="4" cols="44" name="details"><?php echo $row['Details'];?></textarea><br />

                    <label for="imageFile">Select an image to upload:</label>
                    <input type="file" size="40" multiple="multiple" name="imageFile" id="imageFile" /><br><br>

                    <input type="submit" name="editButton" value="Confirm Post Edit"><br><br>
                    <input type="submit" name="deleteButton" value="Delete my post">



                </form>


            </div>
            <?php   //if edit button is succesfully clicked then send data from textboxes into these variables
            if (isset($_POST['editButton']))
            {   
                $purpose   = $_POST['purpose'];
                $sP   = $_POST['startingPoint'];
                $destination   = $_POST['destination'];
                $date   = $_POST['date'];
                $car   = $_POST['car'];
                $cost   = $_POST['cost'];
                $details   = $_POST['details'];

                //it works but this doesnt allow to update the post when everything is entered correctly for some reason
                //                if ( !empty($car) || !empty ($details) ){
                //
                //                    //validation for letters and space, but combinations with special characters and numbers are allowed and saved in database
                //                    if ( !preg_match("/^[a-zA-Z\s]*$/", $car) ){
                //                        $message = "Check Car type/Model: Special characters and numbers are not allowed, for example; <\!@~#/ 0-9> ";
                //                    }
                //                    //validation for letters and space, but combinations with special characters and numbers are allowed and saved in database
                //                    else if ( !preg_match("/^[a-zA-Z\s]*$/", $details) ){
                //                        $message = "Check Extra Details: Special characters and numbers are not allowed, for example; <\!@~#/ 0-9> ";
                //                    }
                //                }

                //validation adapted from Mahtab resources PHP 3 MySQLi Mailing List and https://stackoverflow.com/questions/12778083/regex-with-space-and-letters-only 

                //validation for letters and space, but combinations with special characters and numbers are allowed and saved in database
                if ( !preg_match("/^[a-zA-Z\s]*$/", $sP) ){
                    $message = "Check Starting Point: Special characters and numbers are not allowed, for example; <\!@~#/ 0-9> ";
                }
                //validation for letters and space, but combinations with special characters and numbers are allowed and saved in database
                else if ( !preg_match("/^[a-zA-Z\s]*$/", $destination) ){
                    $message = "Check Destination: Special characters and numbers are not allowed, for example; <\!@~#/ 0-9> ";
                }

                //image upload and its validation adapted from Mahtab resources PHP 3 Image Uploading
                else{
                    //if no image is uploaded then update the data 
                    if ( empty($_FILES['imageFile']['type']) ) {
                        //update the data 
                        mysqli_query($conn, "UPDATE Post Set Starting_Point = '$sP', Destination = '$destination', Date = '$date', Car = '$car', Cost_Sharing = '$cost', Details = '$details' WHERE Username = '$uN' AND ID = '$postID'");
                        //echo "<script> location.href='http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/memberposts.php'; </script>";
                        header("Location:memberposts.php");

                        die();
                    }
                    // Validate uploaded image file
                    else if ( !preg_match( '/gif|png|x-png|jpeg/', $_FILES['imageFile']['type']) ) {
                        die('<p>Only browser compatible images allowed</p></body></html>');
                    }//Check for the size of the file 
                    else if ( $_FILES['imageFile']['size'] > 1048576 ) {
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

                        //insert values into rows
                        //update the data 
                        $sql= 'UPDATE Post Set Starting_Point = "'. $sP .'", Destination = "'. $destination .'", Date = "'. $date .'", Car = "'. $car .'", Cost_Sharing = "'. $cost .'", Details = "'. $details .'", ImageType = "' . $_FILES['imageFile']['type'] . '", ImageName = "' . $_FILES['imageFile']['name']  . '", Image = "' . $image . '"  WHERE ID = "'. $postID . '" ';
                        //if the query didnt work output
                        if ( !(mysqli_query($conn, $sql)) ) {

                            die('<p>Error writing image to database</p></body></html>');
                        } else {
                            //echo "<script> location.href='http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/memberposts.php'; </script>";
                            header("Location:memberposts.php");

                        }}

                }
            }
            else  if (isset($_POST['deleteButton']))
            {   
                $purpose   = $_POST['purpose'];
                $sP   = $_POST['startingPoint'];
                $destination   = $_POST['destination'];
                $date   = $_POST['date'];
                $car   = $_POST['car'];
                $cost   = $_POST['cost'];
                $details   = $_POST['details'];


                //delete the data, and -----------------have the option to delete only the image---------------------- 
                mysqli_query($conn, "DELETE FROM Post WHERE ID = '$postID'");
                //echo "<script> location.href='http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/memberposts.php'; </script>";
                header("Location:memberposts.php");

            }

            //close connection
            mysqli_close($conn);

            ?>

        </div>
        <div class="redAlertEditPost" ><span class="closeButton" onclick="this.parentElement.style.display='none';">&times;</span> <p><?php echo $message; ?> </p> 
        </div>
    </body>
</html>

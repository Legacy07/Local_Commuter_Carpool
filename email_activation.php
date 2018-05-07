<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">


    <head>
        <!-- using the custom css -->    
        <link rel="stylesheet" href="style.css"/>
        <title>Email Activation</title>
    </head>
    <body>
        <!--navigation bar in lists-->
        <ul>
            <li><a href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/home.php">What is Carpool?</a></li>
            <li><a href="#news">Contact</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/login.php">Login</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/search.php">Search</a></li>
        </ul>

        <div>
            <?php
    //declaring variables
    $output = "";
$header = "";


// Database Connection 
$conn = mysqli_connect("mysql.cms.gre.ac.uk","ai6935u","Mertmert2019","mdb_ai6935u");
if (mysqli_connect_errno($conn)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}   
//adapted to activate the email address from - https://code.tutsplus.com/tutorials/how-to-implement-email-verification-for-new-members--net-3824


//if email address is in the url and not empty and if email hash is in the url and not empty then verify the email address
if(isset($_GET['email_address']) && !empty($_GET['email_address']) AND isset($_GET['email_hash']) && !empty($_GET['email_hash'])){
    // Verify data
    //Getting it off url by removing email address string and gets the actual email address and hash of that user
    $email_address = mysqli_real_escape_string($conn, $_GET['email_address']);
    $email_hash = mysqli_real_escape_string($conn, $_GET['email_hash']); // Set hash variable
    
    //email address and if verified is 0 then find the address
    $search = mysqli_query($conn, "SELECT Email_Address, Verified FROM Members WHERE Email_Address='$email_address' AND Email_Hash='$email_hash' AND Verified='0'") or die(mysqli_error($conn)); 
    //find the matching rows
    $match  = mysqli_num_rows($search);
    
    //if there is a matching result of more than 1 then activate, because a user can have multiple email addresses to sign up with a different username but email hash comes into play to avoid any confusion in usernames and email addresses
    if($match > 0){
        // Activate the account
        mysqli_query($conn, "UPDATE Members SET Verified = 1 WHERE Email_Address='$email_address'AND Email_Hash='$email_hash'") or die(mysqli_error($conn));
        $output = "Your account has been activated, you can now login";
        $header = "Congratulations!";
    }else{
        // Invalid url or account has already been activated.
        $output = "The url may be invalid or the account was previously activated. Try to copy and paste the link or simply log in with your credentials.";
        $header = "Error occured!";
    }

}else{
    // Invalid approach
    $output = "Please click on the link again.";
    $header = "Error occured!";

}
            ?>
            <div class="boxHeader"><p><?php echo $header ?></p></div>
            <div class="activatedBox"><p><?php echo $output ?></p></div>
        </div>
    </body>
</html>
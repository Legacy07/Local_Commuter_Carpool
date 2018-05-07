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
} ?>

    <head>
        <!--        mobile view-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- using the custom css -->    
        <link rel="stylesheet" href="style.css"/>
        <title>Login</title>
    </head>
    <body>

        <!--navigation bar in lists-->
        <ul>
            <li><a href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/home.php">What is Carpool?</a></li>
            <li><a href="#news">Contact</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/registration.php">Register</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/login.php">Login</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/search.php">Search</a></li>
        </ul>

        <p class="boxHeader">Login</p>

        <div>
            <!-- login box -->
            <div class="box">
                <form name="searchForm" action="login.php" method="post">

                    <label for="userName">Username</label>
                    <input type="text" id="start" name="userName" placeholder="Username" value="<?php if (isset($_COOKIE['userName'])) {?><?php }?>" required>

                    <label for="passWord">Password</label>
                    <input type="password" id="password" name="passWord" placeholder="Minumum of six characters" required>

                    <input type="submit" name="loginButton" value="Login">
                </form>

            </div>
            <!-- registration link -->
            <div class="smallBox">
                <p>Don't have an account?
                    <a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/registration.php">Register now</a></p> 

            </div>
            <?php
            //if register button is succesfully clicked then send data from textboxes into these variables
            if (isset($_POST['loginButton']))
            {   
                $uN   = $_POST['userName'];
                $pW   = $_POST['passWord'];

                //pulling cipher text out of associated username
                // $existingHash = mysqli_query($conn, "SELECT CipherText FROM Members where Username='$uN'");
                //verifying the password cipher text 
                // $pW   = mysqli_real_escape_string($conn, password_verify($_POST['passWord'], (string)$existingHash));

                //selecting usernames and verified 
                $members = mysqli_query($conn, "SELECT * FROM Members where Username='$uN' AND Verified='1'");
                //pulls members with no verified emails
                $notVerifiedMembers =  mysqli_query($conn, "SELECT * FROM Members where Username='$uN' AND Verified='0'");
                //find the rows where username from cookie is matching and verified is 1
                $count = mysqli_num_rows($members);
                // ''
                $notVerifiedCount =  mysqli_num_rows($notVerifiedMembers);

                //if any username is found then output a message 
                if($count > 0){
                    //associated with username and verified
                    $row = mysqli_fetch_array($members, MYSQLI_ASSOC);

                    //adapted to verfiy the password hash from - http://php.net/manual/en/function.password-verify.php 
                    //because there is an if statement, sql injection cannot be performed as there is a comparison algorithm is going on to get the username and the hash therefore anything apart from the correct inputted username and password will not function and will not trigger sql statement.
                    if(password_verify($pW, $row['CipherText'])){
                        //created session 
                        //$_SESSION['loggedIn']=$uN;

                        //adapted to set cookie from - http://php.net/manual/en/function.setcookie.php
                        //set cookie with 30 days expiry
                        setcookie("userName", $uN, time() + (86400 * 30), "/");

                        //echo "<script> location.href='http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/search.php'; </script>";           
                        header("Location:search.php");

                    } 
                    if ($notVerifiedCount > 0){
                        $message = "An account is yet to be verified. Please check your email address for confirmation";


                    }
                    else
                    {
                        $message = "Login Failed! Username and Password do not match.";
                    }

                }
                //if the inputted username is not verified then output an error message
                else if ($notVerifiedCount > 0){
                    $message = "An account is yet to be verified. Please check your email address for confirmation";
                }
                else {

                    $message = "There is no such account created. Please register first.";
                }

            }
            //close connection
            mysqli_close($conn);

            ?>

        </div>
        <div class="redAlert" ><span class="closeButton" onclick="this.parentElement.style.display='none';">&times;</span> <p><?php echo $message; ?> </p> 
        </div>
        <div class="infoAlert" ><span class="closeButton" onclick="this.parentElement.style.display='none';">&times;</span> <p><?php echo "This site uses cookies to store information on your browser"; ?> </p> 
        </div>
    </body>
</html>

<?php 
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
$verifiedMessage = "";
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
        <title>Register</title>
    </head>
    <body>
        <!--navigation bar in lists-->
        <ul>
            <li><a href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/home.php">What is Carpool?</a></li>
            <li><a href="#news">Contact</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/login.php">Login</a></li>
            <li style="float:right" ><a  href="http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/search.php">Search</a></li>
        </ul>

        <p class="boxHeader">Register</p>

        <div>
            <!-- registration box -->
            <div class="box">
                <form name="phpForm" action="registration.php" method="post">

                    <label for="username">Username</label>
                    <input type="text" id="username" name="userName" placeholder="Username" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="passWord" placeholder="Minumum of six characters" required>

                    <label for="email">E-Mail</label>
                    <input type="email" id="email" name="eMail" placeholder="e.g. jones@hotmail.com" required>

                    <img src="captcha.php" alt="Captcha">
                    <br>
                    Type the word above.
                    <input type="text" id="captcha" name="captcha" required> 

                    <input type="submit" name="registerButton" value="Register">

                    <?php
                    //if register button is succesfully clicked then send data from textboxes into these variables
                    if (isset($_POST['registerButton']))
                    {   
                        $uN   = $_POST['userName'];
                        $pW   = $_POST['passWord'];
                        $eM   = $_POST['eMail'];
                        $notVerified = 0;

                        //adapted from Mahtab's tutorials, Php 3 resources
                        //validation for special characters, but combinations with special characters allowed and saved in database
                        if ( !preg_match("/([a-zA-Z0-9])/", $uN) ){
                            echo "Special characters are not allowed, for example; <\!@~#/> ";
                        }
                        //validation for special characters, but combinations with special characters allowed and saved in database
                        else if ( !preg_match("/([a-zA-Z0-9])/", $pW) ){
                            echo "Special characters are not allowed, for example; <\!@~#/> ";
                        }

                        else if ( !preg_match('/^([\w\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$eM) ){
                            echo "Please enter a valid e-mail";
                        }
                        else{
                            //adapted to hash the password and the email by additional salt using Bcrypt from - http://www.php.net/manual/en/function.password-hash.php 

                            //options for bcrypt uses blowfish encryption, including salt for improving encryption.
                            $options = [
                                'cost' => 11,
                                'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
                            ];
                            //hash the password 
                            $hash = password_hash($pW, PASSWORD_BCRYPT, $options);
                            $hashEmail = password_hash($eM, PASSWORD_BCRYPT, $options);

                            //if captcha session is the same as the entered text in captcha text box its alright else output a message.
                            if(isset($_POST["captcha"]) && $_POST["captcha"]!="" && $_SESSION["captchaString"]==$_POST["captcha"])
                            {

                            }
                            else
                            {
                                echo("Wrong Capthca Entered.");
                            }
                            //finding the usernames using mysqli_query framework.
                            $members = mysqli_query($conn, "SELECT * FROM Members where Username='$uN'");
                            //return the rows of the search
                            $rows = mysqli_affected_rows($conn);
                            //if any username is found then output a message 
                            if($rows >=1){
                                $message = "This username is already being chosen.";
                            }
                            //if not insert the data in table
                            else{
                                //insert values into rows
                                $sql = mysqli_query($conn, "INSERT INTO Members (CipherText, Email_Address, Email_Hash, Username, Verified)
                            VALUES ('$hash', '$eM','$hashEmail', '$uN', '$notVerified')");

                                //adapted to send an email with dynamically created website from - https://code.tutsplus.com/tutorials/how-to-implement-email-verification-for-new-members--net-3824

                                //Sending email with verificaiton link 
                                $to      = $eM; // email of the registered member
                                $subject = 'Account Activation'; 
                                $body = 
                                    '
                            Hello '.$uN.'
                            Your account has been created, you can login after you have activated your account by clicking on the URL below.

                            ----------------------------------------------------------------------------------------------------------------

                            Please click this link to activate your account:
                            http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/email_activation.php?email_address='.$eM.'&email_hash='.$hashEmail.'';

                                $headers = 'From: ai6935u@greenwich.ac.uk' . "\r\n"; // headers
                                $status = mail($to, $subject, $body, $headers); // Sending email

                                if($status)
                                { 
                                    $verifiedMessage ="An activation link has been sent to $eM";
                                } else { 
                                    echo '<p>Something went wrong, Please try again!</p>'; 
                                }


                            }
                        }}

                    //close connection
                    mysqli_close($conn);

                    ?>
                    <!-- output a message which is assigned for duplicate username -->
                    <?php  echo $message;?>
                    <?php echo $verifiedMessage; ?>

                </form>

            </div>
        </div>
    </body>

</html>

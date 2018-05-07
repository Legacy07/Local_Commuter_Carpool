<?php   
//session starts
//session_start();
//output buffering for cookies so it sends out headers before any code
ob_start();
?>

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">


<?php
    //delete the cookie
    //unset($_COOKIE['userName']);

    // empty value and expiry 
    setcookie('userName', '', time() - (86400 * 30), "/");

//echo "<script> location.href='http://stuweb.cms.gre.ac.uk/~ai6935u/web_cw/login.php'; </script>"; 
header("Location:login.php");
ob_flush();

?>

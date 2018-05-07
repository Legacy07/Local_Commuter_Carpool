<?php
//From Mahtab's PHP 3 resource and included only session to compare the strings of captcha with the inputted value on registration page

//start session
session_start();
// Read background image
$image = ImageCreateFromPng ("images/captcha_background.png");
// Randomise the text colour
$red = rand(80,130);
$green = rand(80,130);
$blue = 320 -$red - $green;
$textColour = ImageColorAllocate($image, $red, $green, $blue);

// Randomly select a character string
$charArray = array('a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','6','7','8','9');
shuffle($charArray);
$captchaString = $charArray[0];
for ($i=1; $i<5; $i++) $captchaString .= '' . $charArray[$i];

//Creating a session to send registration page
$_SESSION["captchaString"] = $captchaString ;

// Edit the image
ImageString($image, 5, 10, 10, $captchaString, $textColour);

// Enlarge the image
$bigImage = imagecreatetruecolor(200, 80);
imagecopyresized($bigImage, $image, 0, 0, 0, 0, 200, 80, 100, 40);

// Output the image as a low quality JPEG
header("Content-Type: image/jpeg");
Imagejpeg($bigImage, NULL, 8);

// clean up
ImageDestroy($image);
ImageDestroy($bigImage);
?>
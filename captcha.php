<?php
include 'sqlconnect.php';

$capstring = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
$captcha = substr(str_shuffle($capstring), 0, 6);
$_SESSION['code'] = $captcha;
$im = imagecreatetruecolor(60, 20);
$bg = imagecolorallocate($im, 255, 255, 255);
$textcolor = imagecolorallocate($im, 175, 199, 200);
imagestring($im, 5, 2, 1, $captcha, $textcolor);
header('Content-Type: image/png');
imagepng($im);



 ?>

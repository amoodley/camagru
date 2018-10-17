<?php
include('./config/database.php');
include('./classes/Login.php');
include('./classes/Post.php');
include_once('./classes/Image.php');
include('./classes/Notify.php');

$userid = Login::isLoggedIn();


$photo = $_POST["photo"];
$overlay = $_POST["overlay"];
$photo2 = explode(',', $photo);
$photo = $photo2[1];
$photo = base64_decode($photo);
$overlay2 = explode(',', $overlay);
$overlay = $overlay2[1];
$overlay = base64_decode($overlay);

$img1 = imagecreatefromstring($photo);
$img2 = imagecreatefromstring($overlay);
imagecopy($img1, $img2, 0, 0, 0, 0, 640, 480);

$img_name = tempnam("./image/uploads", $userid . "_");
imagepng($img1, $img_name.".png");
$img_name = substr($img_name, strlen($_SERVER['DOCUMENT_ROOT']));
$img_name .= ".png";
$postid = Post::createImgPost(NULL, Login::isLoggedin(), $userid);
DB::query('UPDATE posts SET postimg=:postimg WHERE id=:postid', array('postimg'=>$img_name,'postid'=>$postid));
echo $img_name;
unlink($img_name);
imagedestroy($img1);
?>
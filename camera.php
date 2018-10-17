<?php 
include('./config/database.php');
include('./classes/Login.php');
include('./classes/Post.php');
include('./classes/Comment.php');
include_once('./classes/Image.php');
include('./classes/Notify.php');

$showTimeline = False;
if (Login::isLoggedIn()) {
	$userid = Login::isLoggedIn();
	$showTimeline = True;
} else {
	header("location: login.php");
}

$username = DB::query('SELECT username FROM users WHERE id=:id', array('id'=>$userid))[0]['username'];
$profileimg = DB::query('SELECT profileimg FROM users WHERE id=:id', array('id'=>$userid))[0]['profileimg'];

if (isset($_POST['logout'])) {

	if (isset($_POST['alldevices'])) {
		
		DB::query('DELETE FROM login_tokens WHERE user_id=:userid', array('userid'=>Login::isLoggedIn()));
		header("location: login.php");
	} else {

		if (isset($_COOKIE['SNID'])) {
			DB::query('DELETE FROM login_tokens WHERE token=:token', array('token'=>sha1($_COOKIE['SNID'])));
		}
		setcookie('SNID', '1', time()-3600);
		setcookie('SNID_', '1', time()-3600);
		header("location: login.php");
	}

} 

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<link rel="icon" type="image/png" href="image/squad_logo_white.png">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Squad</title>
</head>
<body onload="videoStream()">

	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">
					<img src="image/squad_logo2_white.png">
				</a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li>
						<a href="camera.php">
							<span class="glyphicon glyphicon-camera"></span>
						</a>
					</li>
					<li>
						<a href="index.php">
							<span class="glyphicon glyphicon-list-alt"></span>
						</a>
					</li>
					<li>
						<a href="">
							<span class="glyphicon glyphicon-envelope"></span>
						</a>
					</li>
					<li class="dropdown">
						<a href="">
							<span class="glyphicon glyphicon-bell"></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="">Sent you a Message</a>
							</li>
							<li>
								<a href="">Liked your Post</a>
							</li>
							<li>
								<a href="">Mentioned you in a Post</a>
							</li>
						</ul>
					</li>
				</ul>
				<form class="navbar-form navbar-left" action="results.php" method="POST">
					<div class="input-group">
						<input type="text" class="form-control search" placeholder="Search" name="searchbox" value="">
						<div class="input-group-btn">
							<button class="btn btn-default btn-square black" type="submit" name="search" value="Search">
								<i class="glyphicon glyphicon-search"></i>
							</button>
						</div>
					</div>
				</form>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<div class="navbar-brand">
							<a class="navbar-brand" class="dropdown-toggle" data-toggle="dropdown" href="profile.php?username=<?php echo $username; ?>">
								<img class="img-circle" src="<?php echo $profileimg; ?>">
							</a>
						</div>
						<ul class="dropdown-menu">
							<form action="index.php" method="POST">
								<li>
									<button type="submit" name="logout" value="Logout" class="btn btn-default btn-block">
										Logout 
										<span class="glyphicon glyphicon-off"></span>
									</button>
								</li>
								<li class="divider"></li>
								<li>
									<div class="checkbox">
										<label><input type="checkbox"  name="alldevices" value="alldevices">All Devices</label>
									</div>
								</li>
							</form>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="top-spacer"></div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-8">
				<div class="jumbotron">
					<div class="wrap">
						<div id="video_overlay">
							<img class="overlay" id="overlay">
							<canvas id="myOverlay" width="640" height="480"></canvas>
						</div>
						<div id="video_div">
							<video id="video" width="100%" height="100%" autoplay></video>
							<canvas id="canvas" width="640" height="480"></canvas>
						</div>
					</div>
						<a href="#" id="capture" class="booth-capture-button" onclick="snapshot()">Take Photo</a>
						<a href="#" id="newPhoto" class="booth-capture-button" onclick="newPhoto()">Take Another Photo</a>
						<a href="camera.php" id="post" class="booth-post-button pull-right"onclick="postImage()">Post Photo</a>
							
							<br>
						<div class="scrolls" id="style1">

							<!-- filters -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/batman.png');">
								<img class="filter" src="image/filters/batman.png" alt="batman">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/bowtie.png');">
								<img class="filter" src="image/filters/bowtie.png" alt="bowtie">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/bunny_face.png');">
								<img class="filter" src="image/filters/bunny_face.png" alt="bunny face">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/bunny_face_2.png');">
								<img class="filter" src="image/filters/bunny_face_2.png" alt="bunny face 2">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/cat_face.png');">
								<img class="filter" src="image/filters/cat_face.png" alt="cat face">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/cat_face_2.png');">
								<img class="filter" src="image/filters/cat_face_2.png" alt="cat face 2">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/clown_nose.png');">
								<img class="filter" src="image/filters/clown_nose.png" alt="clown nose">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/dog_ears.png');">
								<img class="filter" src="image/filters/dog_ears.png" alt="dog ears">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/dog_face.png');">
								<img class="filter" src="image/filters/dog_face.png" alt="dog face">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/eyes.png');">
								<img class="filter" src="image/filters/eyes.png" alt="eyes">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/gas_mask.png');">
								<img class="filter" src="image/filters/gas_mask.png" alt="gas mask">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/hat.png');">
								<img class="filter" src="image/filters/hat.png" alt="hat">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/hearts.png');">
								<img class="filter" src="image/filters/hearts.png" alt="hearts">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/mask.png');">
								<img class="filter" src="image/filters/mask.png" alt="mask">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/mic.png');">
								<img class="filter" src="image/filters/mic.png" alt="mic">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pirate.png');">
								<img class="filter" src="image/filters/pirate.png" alt="pirate">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/rainbow.png');">
								<img class="filter" src="image/filters/rainbow.png" alt="rainbow">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/top_hat.png');">
								<img class="filter" src="image/filters/top_hat.png" alt="top hat">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/unicorn_face.png');">
								<img class="filter" src="image/filters/unicorn_face.png" alt="unicorn face">
							</button>

							<!-- meme -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/deal_with_it.png');">
								<img class="filter" src="image/filters/deal_with_it.png" alt="deal with it">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/doge.png');">
								<img class="filter" src="image/filters/doge.png" alt="doge">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/doge_dealwithit.png');">
								<img class="filter" src="image/filters/doge_dealwithit.png" alt="doge dealwithit">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/grumpy_cat.png');">
								<img class="filter" src="image/filters/grumpy_cat.png" alt="grumpy cat">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/harambe.png');">
								<img class="filter" src="image/filters/harambe.png" alt="harambe">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pepe_feelsbadman.png');">
								<img class="filter" src="image/filters/pepe_feelsbadman.png" alt="pepe feelsbadman">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pepe_feelsgoodman.png');">
								<img class="filter" src="image/filters/pepe_feelsgoodman.png" alt="pepe feelsgodman">
							</button>

							<!-- food -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/beer.png');">
								<img class="filter" src="image/filters/beer.png" alt="beer">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/cake.png');">
								<img class="filter" src="image/filters/cake.png" alt="cake">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/candy_floss.png');">
								<img class="filter" src="image/filters/candy_floss.png" alt="candy floss">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/chinese_food.png');">
								<img class="filter" src="image/filters/chinese_food.png" alt="chinese food">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/cupcake.png');">
								<img class="filter" src="image/filters/cupcake.png" alt="cupcake">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/donut.png');">
								<img class="filter" src="image/filters/donut.png" alt="donut">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/ice_cream.png');">
								<img class="filter" src="image/filters/ice_cream.png" alt="ice-cream">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/lollipop.png');">
								<img class="filter" src="image/filters/lollipop.png" alt="lollipop">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pizza.png');">
								<img class="filter" src="image/filters/pizza.png" alt="pizza">
							</button>

							<!-- holiday -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/bat.png');">
								<img class="filter" src="image/filters/bat.png" alt="bat">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pumpkin.png');">
								<img class="filter" src="image/filters/pumpkin.png" alt="pumpkin">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/christmas_hat.png');">
								<img class="filter" src="image/filters/christmas_hat.png" alt="christmas hat">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/christmas_tree.png');">
								<img class="filter" src="image/filters/christmas_tree.png" alt="christmas tree">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/diwali.png');">
								<img class="filter" src="image/filters/diwali.png" alt="diwali">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/hannuka.png');">
								<img class="filter" src="image/filters/hannuka.png" alt="hannuka">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/dragon.png');">
								<img class="filter" src="image/filters/dragon.png" alt="dragon">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/easter.png');">
								<img class="filter" src="image/filters/easter.png" alt="easter">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/july_4th.png');">
								<img class="filter" src="image/filters/july_4th.png" alt="july 4th">
							</button>

							<!-- thug life -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/ciggarette.png');">
								<img class="filter" src="image/filters/ciggarette.png" alt="100">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/joint.png');">
								<img class="filter" src="image/filters/joint.png" alt="android">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/snoop.png');">
								<img class="filter" src="image/filters/snoop.png" alt="blush">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/thug_lama.png');">
								<img class="filter" src="image/filters/thug_lama.png" alt="angry bird">
							</button>

							<!-- gaming -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/angry_bird.png');">
								<img class="filter" src="image/filters/angry_bird.png" alt="angry bird">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/mario.png');">
								<img class="filter" src="image/filters/mario.png" alt="beer">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pac-man.png');">
								<img class="filter" src="image/filters/pac-man.png" alt="beer">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pac-man_ghost.png');">
								<img class="filter" src="image/filters/pac-man_ghost.png" alt="blush">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/sonic.png');">
								<img class="filter" src="image/filters/sonic.png" alt="100">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/stars.png');">
								<img class="filter" src="image/filters/stars.png" alt="android">
							</button>

							<!-- anime -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/pika_angry.png');">
								<img class="filter" src="image/filters/pika_angry.png" alt="pika angry">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pika_happy.png');">
								<img class="filter" src="image/filters/pika_happy.png" alt="pika happy">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/bulbasaur.png');">
								<img class="filter" src="image/filters/bulbasaur.png" alt="bubasaur">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/squirtle.png');">
								<img class="filter" src="image/filters/squirtle.png" alt="squirtle">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/butterfree.png');">
								<img class="filter" src="image/filters/butterfree.png" alt="butterfree">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/charizard.png');">
								<img class="filter" src="image/filters/charizard.png" alt="charizard">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/eevee.png');">
								<img class="filter" src="image/filters/eevee.png" alt="eevee">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/jigglypuff.png');">
								<img class="filter" src="image/filters/jigglypuff.png" alt="jigglypuff">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/meowth.png');">
								<img class="filter" src="image/filters/meowth.png" alt="meowth">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/naruto.png');">
								<img class="filter" src="image/filters/naruto.png" alt="naruto">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/chibi_1.png');">
								<img class="filter" src="image/filters/chibi_1.png" alt="chibi 1">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/chibi_2.png');">
								<img class="filter" src="image/filters/chibi_2.png" alt="chibi 2">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/chibi_3.png');">
								<img class="filter" src="image/filters/chibi_3.png" alt="chibi 3">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/chibi_4.png');">
								<img class="filter" src="image/filters/chibi_4.png" alt="chibi 4">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/chibi_5.png');">
								<img class="filter" src="image/filters/chibi_5.png" alt="chibi 5">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/saitama.png');">
								<img class="filter" src="image/filters/saitama.png" alt="saitama">
							</button>

							<!-- hello kitty -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/hellokitty.png');">
								<img class="filter" src="image/filters/hellokitty.png" alt="alien">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/hellokitty_ballet.png');">
								<img class="filter" src="image/filters/hellokitty_ballet.png" alt="android">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/hellokitty_balloon.png');">
								<img class="filter" src="image/filters/hellokitty_balloon.png" alt="angry">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/hellokitty_flower.png');">
								<img class="filter" src="image/filters/hellokitty_flower.png" alt="angry bird">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/hellokitty_heart.png');">
								<img class="filter" src="image/filters/hellokitty_heart.png" alt="apple">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/hellokitty_paint.png');">
								<img class="filter" src="image/filters/hellokitty_paint.png" alt="bat">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/hellokitty_photo.png');">
								<img class="filter" src="image/filters/hellokitty_photo.png" alt="batman">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/hellokitty_wave.png');">
								<img class="filter" src="image/filters/hellokitty_wave.png" alt="beer">
							</button>

							<!-- pusheen -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen.png');">
								<img class="filter" src="image/filters/pusheen.png" alt="blush">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_cookie.png');">
								<img class="filter" src="image/filters/pusheen_cookie.png" alt="100">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_cry.png');">
								<img class="filter" src="image/filters/pusheen_cry.png" alt="alien">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_donut.png');">
								<img class="filter" src="image/filters/pusheen_donut.png" alt="android">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_friends.png');">
								<img class="filter" src="image/filters/pusheen_friends.png" alt="angry">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_harry_potter.png');">
								<img class="filter" src="image/filters/pusheen_harry_potter.png" alt="angry bird">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_ice_cream.png');">
								<img class="filter" src="image/filters/pusheen_ice_cream.png" alt="apple">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_love.png');">
								<img class="filter" src="image/filters/pusheen_love.png" alt="bat">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_mcdonalds.png');">
								<img class="filter" src="image/filters/pusheen_mcdonalds.png" alt="batman">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_noodles.png');">
								<img class="filter" src="image/filters/pusheen_noodles.png" alt="beer">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_phone.png');">
								<img class="filter" src="image/filters/pusheen_phone.png" alt="blush">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_sleep.png');">
								<img class="filter" src="image/filters/pusheen_sleep.png" alt="100">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/pusheen_unicorn.png');">
								<img class="filter" src="image/filters/pusheen_unicorn.png" alt="alien">
							</button>

							<!-- rilakumma -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/rilakumma_candy.png');">
								<img class="filter" src="image/filters/rilakumma_candy.png" alt="angry bird">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/rilakumma_chill.png');">
								<img class="filter" src="image/filters/rilakumma_chill.png" alt="apple">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/rilakumma_chill.png');">
								<img class="filter" src="image/filters/rilakumma_cookie.png" alt="bat">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/rilakumma_cozy.png');">
								<img class="filter" src="image/filters/rilakumma_cozy.png" alt="batman">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/rilakumma_fish.png');">
								<img class="filter" src="image/filters/rilakumma_fish.png" alt="beer">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/rilakumma_fish_1.png');">
								<img class="filter" src="image/filters/rilakumma_fish_1.png" alt="blush">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/rilakumma_fish_1.png');">
								<img class="filter" src="image/filters/rilakumma_friends.png" alt="100">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/rilakumma_panda.png');">
								<img class="filter" src="image/filters/rilakumma_panda.png" alt="alien">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/rilakumma_shout.png');">
								<img class="filter" src="image/filters/rilakumma_shout.png" alt="android">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/rilakumma_wave.png');">
								<img class="filter" src="image/filters/rilakumma_wave.png" alt="angry">
							</button>

							<!-- emoticons -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/100.png');">
								<img class="filter" src="image/filters/100.png" alt="100">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/alien.png');">
								<img class="filter" src="image/filters/alien.png" alt="alien">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/angry.png');">
								<img class="filter" src="image/filters/angry.png" alt="angry">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/blush.png');">
								<img class="filter" src="image/filters/blush.png" alt="blush">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/camera.png');">
								<img class="filter" src="image/filters/camera.png" alt="camera">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/cry.png');">
								<img class="filter" src="image/filters/cry.png" alt="cry">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/cry_1.png');">
								<img class="filter" src="image/filters/cry_1.png" alt="cry 1">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/dog.png');">
								<img class="filter" src="image/filters/dog.png" alt="dog">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/fear.png');">
								<img class="filter" src="image/filters/fear.png" alt="fear">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/fire.png');">
								<img class="filter" src="image/filters/fire.png" alt="afire">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/flex.png');">
								<img class="filter" src="image/filters/flex.png" alt="flex">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/ghost.png');">
								<img class="filter" src="image/filters/ghost.png" alt="ghost">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/grin.png');">
								<img class="filter" src="image/filters/grin.png" alt="grin">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/horse.png');">
								<img class="filter" src="image/filters/horse.png" alt="horse">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/laugh.png');">
								<img class="filter" src="image/filters/laugh.png" alt="laugh">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/lips.png');">
								<img class="filter" src="image/filters/lips.png" alt="lips">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/love.png');">
								<img class="filter" src="image/filters/love.png" alt="love">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/money_bag.png');">
								<img class="filter" src="image/filters/money_bag.png" alt="money bag">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/monkey_see.png');">
								<img class="filter" src="image/filters/monkey_see.png" alt="monkey see">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/monkey_speak.png');">
								<img class="filter" src="image/filters/monkey_speak.png" alt="monkey speak">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/moon.png');">
								<img class="filter" src="image/filters/moon.png" alt="moon">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/omg.png');">
								<img class="filter" src="image/filters/omg.png" alt="omg">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/panda.png');">
								<img class="filter" src="image/filters/panda.png" alt="panda">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/poop.png');">
								<img class="filter" src="image/filters/poop.png" alt="poop">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/right.png');">
								<img class="filter" src="image/filters/right.png" alt="right">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/shock.png');">
								<img class="filter" src="image/filters/shock.png" alt="shock">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/sleep.png');">
								<img class="filter" src="image/filters/sleep.png" alt="sleep">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/sunglasses.png');">
								<img class="filter" src="image/filters/sunglasses.png" alt="sunglasses">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/thumbs_up.png');">
								<img class="filter" src="image/filters/thumbs_up.png" alt="thumbs up">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/tongue.png');">
								<img class="filter" src="image/filters/tongue.png" alt="tongue">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/umbrella.png');">
								<img class="filter" src="image/filters/umbrella.png" alt="umbrella">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/unicorn.png');">
								<img class="filter" src="image/filters/unicorn.png" alt="unicorn">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/wink.png');">
								<img class="filter" src="image/filters/wink.png" alt="wink">
							</button>

							<!-- misc -->
							<button class="overlayButton" onclick="changeOverlay('image/filter/android.png');">
								<img class="filter" src="image/filters/android.png" alt="android">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/apple.png');">
								<img class="filter" src="image/filters/apple.png" alt="apple">
							</button>
							<button class="overlayButton" onclick="changeOverlay('image/filter/slippery.png');">
								<img class="filter" src="image/filters/slippery.png" alt="beer">
							</button>

						</div><!-- scrolls -->
						
				</div>
			</div>

<?php 
$recent = DB::query('SELECT postimg FROM `posts` WHERE user_id=:userid ORDER BY posted_at DESC LIMIT 3;', array('userid'=>$userid));
?>
			<div class="col-sm-4">
				<div class="jumbotron">
					<?php  
					foreach ($recent as $photo) {
						echo '<img id="photo" src="'.$photo['postimg'].'"class="img-responsive" alt="Photo" /><br>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="js/photo.js"></script>
</body>
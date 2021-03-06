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
$works = DB::query('SELECT worksat FROM details WHERE user_id=:id', array('id'=>$userid))[0]['worksat'];
$lives = DB::query('SELECT livesat FROM details WHERE user_id=:id', array('id'=>$userid))[0]['livesat'];
$born = DB::query('SELECT birthday FROM details WHERE user_id=:id', array('id'=>$userid))[0]['birthday'];
$born = strtotime($born);
$born = date('Y-m-d', $born);

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

if (isset($_POST['post'])) {
	if ($_FILES['postimg']['size'] == 0) {
		Post::createPost(strip_tags($_POST['postbody']), Login::isLoggedIn(), $userid);
	} else {
		$postid = Post::createImgPost(strip_tags($_POST['postbody']), Login::isLoggedIn(), $userid);
		Image::uploadImage('postimg', "UPDATE posts SET postimg=:postimg WHERE id=:postid", array('postid'=>$postid));
	}
}


if (isset($_GET['postid'])) {
	Post::likePost($_GET['postid'], $userid);
}

if (isset($_POST['comment'])) {
	Comment::createComment(strip_tags($_POST['commentbody']), $_GET['postid'], $userid);
}

if (isset($_POST['searchbox'])) {
	$tosearch = explode(" ", strip_tags($_POST['searchbox']));
	if (count($tosearch) == 1) {
		$tosearch = str_split($tosearch[0], 2);
	}
	$whereclause = "";
	$paramsarray = array(':username'=>'%'.$_POST['searchbox'].'%');
	for ($i = 0; $i < count($tosearch); $i++) {
		$whereclause .= " OR username LIKE :u$i ";
		$paramsarray[":u$i"] = $tosearch[$i];
	}
	$users = DB::query('SELECT users.username FROM users WHERE users.username LIKE :username '.$whereclause.'', $paramsarray);
	print_r($users);

	$whereclause = "";
	$paramsarray = array(':body'=>'%'.$_POST['searchbox'].'%');
	for ($i = 0; $i < count($tosearch); $i++) {
		if ($i % 2) {
			$whereclause .= " OR body LIKE :p$i ";
			$paramsarray[":p$i"] = $tosearch[$i];
		}
	}
	$posts = DB::query('SELECT posts.body FROM posts WHERE posts.body LIKE :body '.$whereclause.'', $paramsarray);
	echo '<pre>';
	print_r($posts);
	echo '</pre>';
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
<body>

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

<!-- <div class="container">
<div class="searchbox">
	<form action="index.php" method="POST">
		<input type="text" name="searchbox" value="">
		<input type="submit" name="search" value="Search">
	</form>
</div> -->

<?php

// $followingposts = "";
// $followers_1 = DB::query('SELECT followers.follower_id From followers WHERE followers.user_id = :userid;', array('userid'=>$userid));
// foreach ($followers_1 as $val)
// {
// 	print_r($val);
// 	foreach ($val as $key)
// 	{
// 		echo $key; 	
// 		$followingposts .=  DB::query('SELECT posts.id, posts.body, posts.likes, posts.posted_at, posts.postimg, users.`username`, users.`profileimg` 
// 		FROM posts INNER JOIN users ON posts.user_id = users.id 
// 		WHERE posts.user_id = :user', array('user'=>$key));
// 	}
// }


$followingposts = DB::query('SELECT posts.id, posts.body, posts.likes, posts.posted_at, posts.postimg, users.`username`, users.`profileimg` FROM users, posts, followers
	WHERE posts.user_id = followers.user_id
	AND users.id = posts.user_id
	AND follower_id = :userid
	ORDER BY posts.likes DESC;', array('userid'=>$userid));
?>
	<div class="col-sm-3">
		<div class="jumbotron">
			<a href="profile.php?username=<?php echo $username; ?>" class="name-link" title="">
				<h3 class="text-center">
					<?php echo $username; ?>
					<?php if ($verified) { echo ' <span class="glyphicon glyphicon-ok-sign"></span>';} ?>
				</h3>
			</a>
			<img class="img-responsive img-circle" src="<?php echo $profileimg; ?>">
			<p></p>
			<a href="my-account.php" class="btn btn-primary center-block">Edit Profile</a>
			<hr />
			<p class="text-center jumbotron_p"><span class="glyphicon glyphicon-pencil"></span>  <?php echo $works; ?></p>
			<p class="text-center jumbotron_p"><span class="glyphicon glyphicon-home"></span>  <?php echo $lives; ?></p>
			<p class="text-center jumbotron_p"><span class="glyphicon glyphicon-gift"></span> <?php echo $born; ?></p>
		</div>
	</div> <!-- Left Column -->

	<div class="col-sm-9">

		<div class='jumbotron'>
			<div class='form-group'>
				<form action='index.php' method='POST' enctype='multipart/form-data'>
					<p>Status:</p>
					<textarea class='form-control' name='postbody' rows='5' id='status'></textarea>
					<p></p>
					<button type='submit' name='post' value='Post' class='btn btn-primary'>
						<span class='glyphicon glyphicon-pencil'></span> Post
					</button>
					<div class='upload-btn-wrapper'>
						<button type='button' class='btn btn-primary'>
							<span class='glyphicon glyphicon-picture'></span>
						</button>
						<input type='file' name='postimg'> Upload an image
					</div>

				</form>
			</div>
		</div>

		<?php
		foreach ($followingposts as $post) {

		// echo $post['body']." ~ ".$post['username'];
		// echo "<form action='index.php?postid=".$post['id']."' method='POST'>";

		// if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array('postid'=>$post['id'], 'userid'=>$userid))) {

		// 	echo "<input type='submit' name='like' value='Like'>";

		// } else {

		// 	echo "<input type='submit' name='unlike' value='Unlike'>";
		// }

		// echo "<span>".$post['likes']." likes</span>
		// 		</form>
		// 		<form action='index.php?postid=".$post['id']."' method='POST'>
		// 			<textarea name='commentbody' rows='3' cols='50'></textarea>
		// 			<input type='submit' name='comment' value='Comment'>
		// 		</form>
		// 		";
		// 		Comment::displayComments($post['id']);
		// 		echo"
		// 		<hr /><br />";

			echo "<div class='jumbotron'>
			<img class='img-circle thumb' src='".$post['profileimg']."'>
			<p class='time'>".$post['posted_at']."</p>
			<a href='profile.php?username=".$post['username']."' title='' class='name-link'>
			<h4>".$post['username']."</h4>
			</a>
			<br>
			<br>
			<hr>
			<img class='img-responsive' src='".$post['postimg']."'>
			<hr>
			<p>".Post::link_add($post['body'])."</p>
			<br>
			<form action='index.php?postid=".$post['id']."' class='like' method='POST'>";

			if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array('postid'=>$post['id'], 'userid'=>$userid))) {
				echo "<button type='submit' name='like' value='Like' class='btn btn-primary btn-square'>
				<span class='glyphicon glyphicon-heart'></span>
				</button>";
			} else {
				echo "<button type='submit' name='unlike' value='unlike' class='btn btn-danger btn-square'>
				<span class='glyphicon glyphicon-heart'></span>
				</button>";
			}
			echo " ".$post['likes']." Likes
			</form>
			<button type='submit' name='comment' form='comment' value='Comment' class='btn btn-primary pull-right btn-square'>
			<span class='glyphicon glyphicon-comment'></span> Comment
			</button>
			<br />
			<form action='index.php?postid=".$post['id']."' id='comment' method='POST'>
			<br />
			<textarea class='form-control' name='commentbody' rows='2'></textarea>
			</form>
			<br />
			";
			Comment::displayComments($post['id']);
			echo "</div>";
		}

		?>

				<!-- <div class="jumbotron">
					<img class="img-circle thumb" src="image/avatar.png">
					<p class="time">Time</p>
					<h4>Username</h4>
					<br>
					<br>
					<br>
					<hr>
					<p>Sample Text From Post</p>
					<img class="img-responsive" src="image/background/bg_10.jpg">
					<br>
					<button type="button" class="btn btn-primary">
							<span class="glyphicon glyphicon-heart"></span>
					</button>
					0 likes
					<button type="button" class="btn btn-primary pull-right">
							<span class="glyphicon glyphicon-remove"></span> Delete Post
					</button>
				</div> -->

			</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
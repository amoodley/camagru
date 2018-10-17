<?php
include('./config/database.php');
include('./classes/Login.php');
include_once('./classes/Image.php');

if (Login::isLoggedIn()) {
	$userid = Login::isLoggedIn();
} else {
	die('Not Logged In');
}

$username = DB::query('SELECT username FROM users WHERE id=:id', array('id'=>$userid))[0]['username'];
$profileimg = DB::query('SELECT profileimg FROM users WHERE id=:id', array('id'=>$userid))[0]['profileimg'];
$works = DB::query('SELECT worksat FROM details WHERE user_id=:id', array('id'=>$userid))[0]['worksat'];
$lives = DB::query('SELECT livesat FROM details WHERE user_id=:id', array('id'=>$userid))[0]['livesat'];
$born = DB::query('SELECT birthday FROM details WHERE user_id=:id', array('id'=>$userid))[0]['birthday'];
$born = strtotime($born);
$born = date('Y-m-d', $born);
$mailPref = DB::query('SELECT email FROM details WHERE user_id=:userid', array('userid'=>$userid))[0][email];

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

if ($_POST['uploadprofileimg'] != NULL) {

	Image::uploadImage('profileimg', 'UPDATE users SET profileimg=:profileimg WHERE id=:userid', array('userid'=>$userid));

}

if (isset($_POST['editdetails'])) {
	DB::query('INSERT INTO details VALUES(NULL, :userid, :firstName, :lastName, :worksAt, :livesAt, :birthday, NULL)', array('userid'=>$userid,'firstName'=>$_POST['firstName'], 'lastName'=>$_POST['lastName'], 'worksAt'=>$_POST['worksAt'], 'livesAt'=>$_POST['livesAt'], 'birthday'=>$_POST['birthday']));
}

if (isset($_POST['changeUsername'])) {
	DB::query('UPDATE users SET username=:username WHERE id=:userid', array('username'=>$_POST['username'], 'userid'=>$userid));
}

if (isset($_POST['changeEmail'])) {
	DB::query('UPDATE users SET email=:email WHERE id=:userid', array('email'=>$_POST['mail'], 'userid'=>$userid));
}

if (isset($_POST['emailpreferences'])) {
	if ($_POST['email'] == "on") {
		DB::query('UPDATE details SET email=\'1\' WHERE user_id=:userid', array('userid'=>$userid));
	} else if ($_POST['email'] == "off") {
		DB::query('UPDATE details SET email=\'0\' WHERE user_id=:userid', array('userid'=>$userid));
	}
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
					<hr />
					<p class="text-center jumbotron_p"><span class="glyphicon glyphicon-pencil"></span>  <?php echo $works; ?></p>
					<p class="text-center jumbotron_p"><span class="glyphicon glyphicon-home"></span>  <?php echo $lives; ?></p>
					<p class="text-center jumbotron_p"><span class="glyphicon glyphicon-gift"></span> <?php echo $born; ?></p>
				</div>
			</div> <!-- Left Column -->

			<div class="col-sm-9">

				<div class='jumbotron'>
					<div class='form-group'>
						<h1>My Account</h1>
						<form action="my-account.php" method="POST" enctype="multipart/form-data">
							Upload a profile image:
							<input type="file" class="btn btn-primary" name="profileimg">
							<br>
							<input type="submit" class="btn btn-primary" name="uploadprofileimg" value="Upload Image">
						</form>

						<form action="my-account.php" method="POST" enctype="multipart/form-data">
							<h3>Personal Details</h3>
							<label for="firstName">First Name</label>
							<input type="text" class="form-control" name="firstName" id="firstName" placeholder="First Name"><br>
							<label for="lastName">Last Name</label>
							<input type="text" class="form-control" name="lastName" id="lastName" placeholder="Last Name"><br>
							<label for="worksAt">Works at?</label>
							<input type="text" class="form-control" name="worksAt" id="worksAt" placeholder="Where Do you Work?"><br>
							<label for="livesAt">Lives at?</label>
							<input type="text" class="form-control" name="livesAt" id="livesAt" placeholder="Where do you Live?"><br>
							<label for="birthday">Born on?</label>
							<input type="date" class="form-control" name="birthday" id="birthday"><br>
							<input type="submit" class="btn btn-primary" name="editdetails">
						</form>

						<form action="my-account.php" method="POST" enctype="multipart/form-data">
							<h3>Account Details</h3>
							<label for="username">Change Username</label>
							<input type="text" class="form-control" name="username" id="username">
							<br>
							<input type="submit" class="btn btn-primary" name="changeUsername">
							<br>
							<br>
							<label for="email">Change Email</label>
							<input type="text" class="form-control" name="mail" id="mail">
							<br>
							<input type="submit" class="btn btn-primary" name="changeEmail">

						</form>

						<form action="my-account.php" method="POST" enctype="multipart/form-data">
							<h3>Email Notifications</h3>
							<label for="on">On</label>
							<input type="radio" id="on" name="email" value="on" <?php if ($mailPref == 1) { echo "checked"; } ?>>
							<label for="off">Off</label>
							<input type="radio" id="off" name="email" value="off" <?php if ($mailPref == 0) { echo "checked"; } ?>>
							<input type="submit" class="btn btn-primary" name="emailpreferences">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
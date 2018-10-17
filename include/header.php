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
				<a class="navbar-brand" href="profile.html">
					<img src="image/squad_logo2_white.png">
				</a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li>
						<a href="">
							<span class="glyphicon glyphicon-camera"></span>
						</a>
					</li>
					<li>
						<a href="">
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
				<form class="navbar-form navbar-left" action="/action_page.php">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Search">
						<div class="input-group-btn">
							<button class="btn btn-default" type="submit">
								<i class="glyphicon glyphicon-search"></i>
							</button>
						</div>
					</div>
				</form>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<div class="navbar-brand">
							<a class="navbar-brand" class="dropdown-toggle" data-toggle="dropdown" href="">
								<img class="img-circle" src="image/avatar.png">
							</a>
						</div>
						<ul class="dropdown-menu">
							<li>
								<a href="">Log Out <span class="glyphicon glyphicon-off"></span></a>
							</li>
							<li class="divider"></li>
							<li>
								<div class="checkbox">
									<label><input type="checkbox" value="">All Devices</label>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="top-spacer"></div>
	<div class="container-fluid">
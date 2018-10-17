<?php

include('config/database.php');


if (isset($_POST['login'])) {

	$username = $_POST['username'];
	$password = $_POST['password'];

	if (DB::query('SELECT username FROM users WHERE username=:username', array('username'=>$username))) {

		if (password_verify($password, DB::query('SELECT password FROM users WHERE username=:username', array('username'=>$username))[0]['password'])) {

			echo 'Logged In';
			$cstrong = True;
			$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
			$user_id = DB::query('SELECT id FROM users WHERE username=:username', array('username'=>$username))[0]['id'];
			DB::query('INSERT INTO login_tokens VALUES(NULL, :token, :user_id)', array('token'=>sha1($token), 'user_id'=>$user_id));
			setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
			setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
			header("location: index.php");
		} else {

			echo 'Incorrect Password';
		}

	} else {

		echo 'User not registered';
	}
}
include('./include/header-start.php');
?>

<div class="container">

	<div class="leftSpacer">

	</div>

	<div class="loginContainer">

		<div class="form">

			<form action="login.php" method="POST">
				<h1>Welcome Back</h1>
				<div id="error"><p><?php echo $_SESSION['message'] ?></p></div>
				<input class="full" type="text" name="username" value="" placeholder="Username ..."><p />
				<input class="full" type="password" name="password" value="" placeholder="Password ..."><p />
				<input class="start" type="submit" name="login" value="Login">
				<p class="login">Don't have an Account? <a href="create-account.php">Sign Up</a></p>
				<p class="login">Forgot your password? <a href="forgot-password.php">Reset Password</a></p>
			</form>	
		</div>

		</div><!-- loginContainer -->

	</div><!-- container -->

<?php include('include/footer.php') ?>
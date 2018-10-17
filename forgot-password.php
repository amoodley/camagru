<?php
include('./config/database.php');
include('./classes/Mail.php');

if (isset($_POST['resetpassword'])) {

	$cstrong = True;
	$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
	$email = $_POST['email'];
	$user_id = DB::query('SELECT id FROM users WHERE email=:email', array('email'=>$email))[0]['id'];
	DB::query('INSERT INTO password_tokens VALUES(NULL, :token, :user_id)', array('token'=>sha1($token), 'user_id'=>$user_id));
	Mail::sendResetMail($email, $token);
	echo 'Email Sent<br />';

}
include('./include/header-start.php');
?>

<div class="container">

	<div class="leftSpacer">

	</div>

	<div class="loginContainer">

		<div class="form">
			<h1>Forgot Password</h1>
			<form action="forgot-password.php" method="POST">
				<input class="full" type="text" name="email" value="" placeholder="Email ..."><p />
				<input class="start" type="submit" name="resetpassword" value="Reset Password"><p />
			</form>
		</div>

		</div><!-- loginContainer -->

	</div><!-- container -->

<?php include('include/footer.php') ?>
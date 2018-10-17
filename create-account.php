<?php
include('./config/database.php');
include('./classes/Mail.php');

if (isset($_POST['createaccount'])) {
	$cstrong = True;
	$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];

	if (!DB::query('SELECT username FROM users WHERE username=:username', array('username'=>$username))) {

		if (strlen($username) >= 3 && strlen($username) <= 60) {

			if (preg_match('/[a-zA-Z0-9_-]+/', $username)) {

				if (strlen($password) >= 6 && strlen($password) <= 60) {

					if (filter_var($email,FILTER_VALIDATE_EMAIL)) {

						if (!DB::query('SELECT email FROM users WHERE email=:email', array('email'=>$email))) {

							DB::query('INSERT INTO users VALUES (NULL , :username, :password, :email, \'0\', \'image/avatar.png\')', array('username'=>$username, 'password'=>password_hash($password, PASSWORD_BCRYPT), 'email'=>$email));
							Mail::sendWelcomeMail($email, $token);
							header("location: index.php");

						} else {

							echo 'Email in use';

						}
					} else {

						echo 'Invalid Email';

					}

				} else {

					echo 'Invalid Password';
				}

			} else {

				echo 'Invalid Username';
			}

		} else {

			echo 'Invalid Username';

		}

	} else {

		echo 'User already exists';

	}
}
include('./include/header-start.php');
?>

<div class="container">

	<div class="leftSpacer">

	</div>

	<div class="loginContainer">

		<div class="form">
			<form action="create-account.php" method="POST">
				<h1>Sign Up for Free</h1>
				<input class="full" type="text" name="username" value="" placeholder="Username ..."><p />
				<input class="full" type="password" name="password" value="" placeholder="Password ..."><p />
				<input class="full" type="email" name="email" value="" placeholder="Email Adress ..."><p />
				<p class="terms">By creating an account, you agree to our <a href="terms.php" title="">Terms & Conditions</a></p>
				<input class="start" type="submit" name="createaccount" value="Create Account">
				<p class="login">Already have an Account? <a href="login.php">Log In</a></p>
			</form>
		</div>

	</div><!-- loginContainer -->

    </div><!-- container -->

<?php include('include/footer.php') ?>
<?php
include('./config/database.php');
include('./classes/Login.php');
$tokenIsValid = False;

if (Login::isLoggedIn()) {
	
	if (isset($_POST['changepassword'])) {

		$oldpassword = $_POST['oldpassword'];
		$newpassword = $_POST['newpassword'];
		$newpasswordrepeat = $_POST['newpasswordrepeat'];
		$userid = Login::isLoggedIn();

		if (password_verify($oldpassword, DB::query('SELECT password FROM users WHERE id=:userid', array('userid'=>$userid))[0]['password'])) {

			if ($newpassword == $newpasswordrepeat) {

				if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {

					DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array('newpassword'=>password_hash($newpassword, PASSWORD_BCRYPT), 'userid'=>$userid));
					echo 'Password Changed Successfully';

				}

			} else {
					echo "Passwords don't match";
			}

		} else {
			echo "Incorrect Old Password";
		}
	}

} else {

	if (isset($_GET['token'])) {
		
		$token = $_GET['token'];
		if (DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array('token'=>sha1($token)))) {
			
			$userid = DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array('token'=>sha1($token)))[0]['user_id'];
			$tokenIsValid = True;
			if (isset($_POST['changepassword'])) {

				$newpassword = $_POST['newpassword'];
				$newpasswordrepeat = $_POST['newpasswordrepeat'];

				if ($newpassword == $newpasswordrepeat) {

					if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {

						DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array('newpassword'=>password_hash($newpassword, PASSWORD_BCRYPT), 'userid'=>$userid));
						echo 'Password Changed Successfully';
						DB::query('DELETE FROM password_tokens WHERE user_id=:userid', array('userid'=>$userid));
						header("location: index.php");
					}

				} else {
						echo "Passwords don't match";
				}
	
			}

		} else {
			echo 'Token Invalid';
		}

	} else {

		die('Not Logged In');
	}
}
include('./include/header-start.php');
?>

<div class="container">

	<div class="leftSpacer">

	</div>

	<div class="loginContainer">

		<div class="form">

			<h1>Change your Password</h1>
			<form action="<?php if (!$tokenIsValid) {echo 'change-password.php';} else {echo 'change-password.php?token='.$token;} ?>" method="POST">
				<?php if (!$tokenIsValid) { echo '<input class="full" type="password" name="oldpassword" value="" placeholder="Current Password ..."><br />'; } ?>
				<input class="full" type="password" name="newpassword" value="" placeholder="New Password ..."><br />
				<input class="full" type="password" name="newpasswordrepeat" value="" placeholder="Repeat Password ..."><br />
				<input class="start" type="submit" name="changepassword" value="Change Password">
			</form>

		</div>

		</div><!-- loginContainer -->

	</div><!-- container -->

<?php include('include/footer.php') ?>
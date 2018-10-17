<?php
include('./config/database.php');
include('./classes/Login.php');

if (!Login::isLoggedIn()) {
	die("Not Logged In");
}

if (isset($_POST['confirm'])) {

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

<h1>Logout of your Account?</h1>
<p>Are you sure you'd like to logout?</p>
<form action="logout.php" method="POST">
	<input type="checkbox" name="alldevices" value="alldevices">Logout of all your devices?<br />
	<input type="submit" name="confirm" value="Confirm">
</form>
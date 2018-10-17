<?php
include('./config/database.php');
include('./classes/Login.php');

if (Login::isLoggedIn()) {
	$userid = Login::isLoggedIn();
} else {
	die('Not Logged In'  );
}

if (isset($_POST['send'])) {

	if (DB::query('SELECT id FROM users WHERE id=:receiver', array('receiver'=>$_GET['receiver']))) {

		DB::query("INSERT INTO messages VALUES(NULL, :body, :sender, :receiver, 0)", array('body'=>$_POST['body'], 'sender'=>$userid, 'receiver'=>htmlspecialchars($_GET['receiver'])));
		echo "Message Sent";
	} else {
		die('Invalid ID');
	}
}

?>
<h1>Send a Message</h1>
<form action="send-message.php?receiver=<?php echo htmlspecialchars($_GET['receiver']); ?>" method="POST">
	<textarea name="body" rows="8" cols="80"></textarea>
	<input type="submit" name="send" value="Send Message">

</form>
<?php
class Mail {
	public static function sendWelcomeMail($email, $token) {

		$encoding = "utf-8";
		// Preferences for Subject field
	    $subject_preferences = array(
	        "input-charset" => $encoding,
	        "output-charset" => $encoding,
	        "line-length" => 76,
	        "line-break-chars" => "\r\n"
	    );

	    // Mail Content
		$to = $email;
		$subject = "Registration Confirmation";
		$body = '
				<html>
					<head>
						<title>Welcome to the Squad</title>
						<meta name="viewport" content="width=device-width, initial-scale=1.0">
					</head>
					<body background="https://i.imgur.com/NypeuMP.jpg" style="margin: 0; padding: 0; background-position: center center; background-repeat: no-repeat; background-attachment: fixed;
						background-size: cover;
						background-color: #111111;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td style="font-family: Arial, Helvetica, Sans-serif;">
									<table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
										<tr>
											<td align="center" bgcolor="#303030" style="color: #ffffff; padding: 40px 0 30px 0;">
												<img src="https://i.imgur.com/i26Q4x1.png" alt="Squad" width="400px" height="100px" style="display: block;" />
											</td>
										</tr>
										<tr>
											<td bgcolor="#e8e8e8" style="padding: 40px 30px 40px 30px;">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td style="text-align: center; font-size: 30px;">
															Welcome!
														</td>
													</tr>
													<tr>
														<td style="padding: 40px 30px 40px 30px;">
															We\'re excited to have you get started. First, you need to confirm your account. Just click the button below.
															<br />
														</td>
													<tr>
														<td style="text-align: center;">
															<form action="http://e4r6p11.wethinkcode.co.za:8080/camagru/login.php?token='.$token.'" text-align: center;>
																<input type="submit" value="Confirm Account" name="confirmAccount" style="width: 30%; padding: 12px 20px 12px 20px; margin: 20px 0 20px 0; box-sizing: border-box; border: none; color: #ffffff; background-color: #303030; outline: none;"/>
															</form>
														</td>
													</tr>
													<tr>
														<td style="padding: 40px 30px 40px 30px;">
															<br />
															If that doesnt work, copy and paste the following link in your browser:
															<br />
															<br />
															<a href="http://e4r6p11.wethinkcode.co.za:8080/camagru/login.php?token='.$token.'">e4r6p11.wethinkcode.co.za/camagru/login.php?token='.$token.'</a>
															<br />
															<br />
															Regards,
															<br />
															Squad Admin
															<br />
														</td>
													</tr>
												</table>
										<tr>
											<td bgcolor="#303030" style="color: #ffffff; padding: 40px 30px 40px 30px; font-size: 10px;">
												You received this email because you just signed up for a new account. If you did not do that, you can ignore this email.
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>

					</body>
					</html>
				';
		$from = "DoNotReply@squad.com";

		// Mail header
		$header = "Content-type: text/html; charset=".$encoding." \r\n";
		$header .= "From: ".$from." \r\n";
		$header .= "MIME-Version: 1.0 \r\n";
		$header .= "Content-Transfer-Encoding: 8bit \r\n";
		$header .= "Date: ".date("r (T)")." \r\n";
    	$header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

    	// Send Mail
		mail($to, $subject, $body, $header);
	}

	public static function sendResetMail($email, $token) {

		$encoding = "utf-8";
		// Preferences for Subject field
	    $subject_preferences = array(
	        "input-charset" => $encoding,
	        "output-charset" => $encoding,
	        "line-length" => 76,
	        "line-break-chars" => "\r\n"
	    );

	    // Mail Content
		$to = $email;
		$subject = "Forgot Password";
		$body = '
				<html>
					<head>
						<title>Reset Password</title>
						<meta name="viewport" content="width=device-width, initial-scale=1.0">
					</head>
					<body background="https://i.imgur.com/NypeuMP.jpg" style="margin: 0; padding: 0; background-position: center center; background-repeat: no-repeat; background-attachment: fixed;
						background-size: cover;
						background-color: #111111;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td style="font-family: Arial, Helvetica, Sans-serif;">
									<table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
										<tr>
											<td align="center" bgcolor="#303030" style="color: #ffffff; padding: 40px 0 30px 0;">
												<img src="https://i.imgur.com/i26Q4x1.png" alt="Squad" width="400px" height="100px" style="display: block;" />
											</td>
										</tr>
										<tr>
											<td bgcolor="#e8e8e8" style="padding: 40px 30px 40px 30px;">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td style="text-align: center; font-size: 30px;">
															Welcome!
														</td>
													</tr>
													<tr>
														<td style="padding: 40px 30px 40px 30px;">
															To reset your Squad password just click the button below.
															<br />
														</td>
													<tr>
														<td style="text-align: center;">
															<form action="http://e4r6p11.wethinkcode.co.za:8080/camagru/change-password.php?token='.$token.'" text-align: center;>
																<input type="submit" value="Reset Password" name="resetPassword" style="width: 30%; padding: 12px 20px 12px 20px; margin: 20px 0 20px 0; box-sizing: border-box; border: none; color: #ffffff; background-color: #303030; outline: none;"/>
															</form>
														</td>
													</tr>
													<tr>
														<td style="padding: 40px 30px 40px 30px;">
															<br />
															If that doesnt work, copy and paste the following link in your browser:
															<br />
															<br />
															<a href="http://e4r6p11.wethinkcode.co.za:8080/camagru/change-password.php?token='.$token.'">http://e4r6p11.wethinkcode.co.za:8080/camagru/change-password.php?token='.$token.'</a>
															<br />
															<br />
															Regards,
															<br />
															Squad Admin
															<br />
														</td>
													</tr>
												</table>
										<tr>
											<td bgcolor="#303030" style="color: #ffffff; padding: 40px 30px 40px 30px; font-size: 10px;">
												You received this email because you just tried to reset your password. If you did not do that, you can ignore this email.
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>

					</body>
					</html>
				';
		$from = "DoNotReply@squad.com";

		// Mail header
		$header = "Content-type: text/html; charset=".$encoding." \r\n";
		$header .= "From: ".$from." \r\n";
		$header .= "MIME-Version: 1.0 \r\n";
		$header .= "Content-Transfer-Encoding: 8bit \r\n";
		$header .= "Date: ".date("r (T)")." \r\n";
    	$header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

    	// Send Mail
		mail($to, $subject, $body, $header);
	}
}
?>
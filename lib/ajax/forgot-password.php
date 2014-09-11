<?php 
	if (!$_SESSION) session_start();
	// Auto load the class when it is beeing created
	spl_autoload_register(function ($class) { require_once "../classes/".$class.".class.php"; });

	if ( empty($_POST["email"]) ) {
		die(Translate::string("forgot_password.email_missing"));
	} else {
		require_once "../includes/sanitize-all.php";

		$email = $_POST["email"];

		if (!User::userEmailExist($email)) die(Translate::string("forgot_password.wrong_email"));
		$token = User::insertToken($email);

		if (!$token) die("token insert failed");
		$reset_link = "http://www.finnplus.no/?reset-password=".$token."&email=".urlencode($email);

		$mailto   = $email;
		$subject  = Translate::string("forgot_password.email_subject");

		$headers  = "Mime-Version: 1.0 \r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1 \r\n";
		$headers .= "From: <no-reply@FinnPluss.no> \r\n";
		// $headers .= "Reply-to: info@finnplus.no \r\n";

		$message  = sprintf(Translate::string("forgot_password.email_message"), "<br><a href='".$reset_link."'>", $reset_link."</a><hr>");

		$send_mail = mail($mailto, $subject, $message, $headers);
		// Send the email
		echo ($send_mail) ? Translate::string("forgot_password.email_send_success_message") : Translate::string("forgot_password.email_send_failure_message") ;

	}
?>
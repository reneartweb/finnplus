<?php 
	require_once "../includes/session.php";
	require_once "../includes/sanitize-all.php";

	// Auto load the class when it is beeing created
	spl_autoload_register(function ($class) {
		require_once "../classes/".$class.".class.php";
	});

	if ( empty($_POST["email"]) or empty($_POST["token"]) or empty($_POST["new-reset-password"]) or empty($_POST["confirm-reset-password"]) or empty($_POST["javascript"])  ) {
		die(Translate::string("reset_password_alert.all_fields_required"));
	} 
	if ($_POST["new-reset-password"] != $_POST["confirm-reset-password"] ) {
		die(Translate::string("reset_password_alert.passwords_dont_match"));
	}
		
	$email = $_POST["email"];
	$token = $_POST["token"];
	$new_password = $_POST["new-reset-password"];
	$session_id = session_id();
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$javascript = $_POST["javascript"];
	$browser = $_SERVER['HTTP_USER_AGENT'];

	if (!User::isTokenValid($email, $token) ) {
		die(Translate::string("reset_password_alert.token_expired"));
	}

	$user = new User;
	$reset = $user->resetPassword($email, $new_password);
	if (!$reset or !$user->destroyToken($token)) {
		die(Translate::string("reset_password_alert.something_went_wrong"));
	}
	
	$user->insertLog("password changed",$email, $javascript, $browser, $ip, $session_id);
	$user->checkCredentials($email, $new_password, $javascript, $browser, $ip_address, $session_id );

?>
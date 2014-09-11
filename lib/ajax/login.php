<?php 

	require_once "../includes/session.php";
	require_once "../includes/sanitize-all.php";
	// Auto load the class when it is beeing created
	spl_autoload_register(function ($class) {
		require_once "../classes/".$class.".class.php";
	});

	if ( !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["javascript"])  ) {

		require_once "../classes/Inspekt.php";

		$email = $_POST["email"];
		$password = $_POST["password"];
		$javascript = $_POST["javascript"];
		$browser = $_SERVER['HTTP_USER_AGENT'];
		$session_id = session_id();
		$ip_address = $_SERVER['REMOTE_ADDR'];

		if (!Inspekt::isEmail($email)) {
			die(Translate::string("login_alert.incorrect_email"));
		}

		$user = new User;
		$login = $user->checkCredentials($email, $password, $javascript, $browser,$ip_address, $session_id );
		if ($login) echo "success"; // if ajax return is success javascript will redirect
		
	} else {
		echo Translate::string("login_alert.insert_username_and_password");
	}
?>
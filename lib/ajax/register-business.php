<?php 

	require_once "../includes/session.php";
	require_once "../includes/sanitize-all.php";


	$company_name = $_POST["company_name"];
	$company_number = $_POST["company_number"];
	$company_address = $_POST["company_address"];
	$company_zip = $_POST["company_zip"];
	$phone_2 = $_POST["phone_2"];

	$name = $_POST["name"];
	$phone = $_POST["phone"];
	$email = $_POST["email"];
	$birthday = $_POST["birthday"]; // optional
	$password = $_POST["password"];
	$confirm_password = $_POST["confirm_password"];
	$javascript = $_POST["javascript"];
	$javascript = 1;
	$role_id = 2;
	$lang_id = 1;

	// Auto load the class when it is beeing created
	spl_autoload_register(function ($class) {
		require_once "../classes/".$class.".class.php";
	});

	if ($password != $confirm_password) {
		die(Translate::string("register_alert.passwords_dont_match"));
	}
	
	if ( empty($name) or empty($email) or empty($phone) or empty($password) or empty($confirm_password) ) {
		die(Translate::string("register_alert.fill_out_all_fields"));
	} else {

		try {
			$user = new User;

			$user->company_name = $company_name;
			$user->company_number = $company_number;
			$user->company_address = $company_address;
			$user->company_zip = $company_zip;
			$user->phone_2 = $phone_2;

			$user->registerUser( $name, $role_id, $email, $password, $phone, $lang_id, $birthday , $javascript, true);
			echo Translate::string("register_alert.registration_success_please_login");
		} catch (Exception $e) {
			echo '' .$e->getMessage();
		}

	}
?>
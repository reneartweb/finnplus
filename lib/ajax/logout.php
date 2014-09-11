<?php 
	// Auto load the class when it is beeing created
	spl_autoload_register(function ($class) {
		require_once "../classes/".$class.".class.php";
	});
	require_once "../includes/session.php";
	User::logout();
	User::unsetSession();
	header('Location: ../../');
?>
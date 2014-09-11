<?php 

spl_autoload_register(function ($class) {
	require_once "../../lib/classes/".$class.".class.php";
});

if(!isset($_SESSION)) {
	session_start();
}

if (isset($_POST["user_id"]) && isset($_SESSION["employee"])) {
	$db = new Database;
	$db->query('UPDATE users SET can_login = :can_login WHERE id = :user_id AND role_id != 1');
	$db->bind(':user_id', 	$_POST["user_id"]);
	$db->bind(':can_login', $_POST["can_login"]);

	$permission = ($_POST["can_login"]) ? "Yes" : "No" ;

	$db->insertAdminLog( $_SESSION["employee"], "Changed user ".$_POST["user_id"]." login permission to ".$permission, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], session_id() );
	if ($db->execute()) {
		echo 'success';
	} else {
		echo 'Login Permission Change Failed. Please try again.';
	}
}

?>
<?php 

spl_autoload_register(function ($class) {
	require_once "../../lib/classes/".$class.".class.php";
});

if(!isset($_SESSION)) {
	session_start();
}

if (isset($_POST["userID"])) {
	$db = new Database;
	$db->beginTransaction();
	$db->query("UPDATE users SET role_id=4 WHERE id = :id LIMIT 1");
	$db->bind(':id', $_POST["userID"]);
	$update_user = $db->execute();

	$db->query("UPDATE products SET status_id=4 WHERE user_id = :id ");
	$db->bind(':id', $_POST["userID"]);
	$update_products = $db->execute();
	$db->endTransaction();

	$db->insertAdminLog( $_SESSION["employee"], "Deleted user ".$_POST["userID"]." and all user advertisements", $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], session_id() );

	if ($update_products && $update_user) {
		echo 'success';
	} else {
		echo 'Deleting user was not successful. Please try again.';
	}
}



?>
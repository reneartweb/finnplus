<?php 

spl_autoload_register(function ($class) {
	require_once "../../lib/classes/".$class.".class.php";
});

if(!isset($_SESSION)) {
	session_start();
}

if (isset($_POST["id"]) && isset($_POST["name"]) && isset($_SESSION["employee"]) ) {
	$db = new Database;
	$db->query("UPDATE categories_sub SET main_cat_id = 0 WHERE id = :id LIMIT 1");
	// $db->bind(':main_cat_id', $_POST["main_cat_id"]);
	$db->bind(':id', $_POST["id"]);

	$db->insertAdminLog( $_SESSION["employee"], "Deleted subcategory ".$_POST["name"], $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], session_id() );

	if ($db->execute()) {
		echo 'success';
	} else {
		echo 'Deleting category failed. Please try again.';
	}
} else {
	echo "Missing info";
}



?>
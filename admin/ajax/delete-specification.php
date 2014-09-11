<?php 

spl_autoload_register(function ($class) {
	require_once "../../lib/classes/".$class.".class.php";
});

if(!isset($_SESSION)) {
	session_start();
}

if (isset($_POST["id"]) && isset($_SESSION["employee"])) {
	$db = new Database;

	$db->query("SELECT name FROM specs WHERE id = :id LIMIT 1");
	$db->bind(':id', $_POST["id"]);
	$spec = $db->single();
	$spec or die("No spec name");

	$db->query("DELETE FROM product_specs WHERE spec_id = :spec_id");
	$db->bind(':spec_id', $_POST["id"]);
	$db->execute() or die("Could not delete product specs");

	$db->query("DELETE FROM specs WHERE id = :id LIMIT 1");
	$db->bind(':id', $_POST["id"]);
	$db->execute() or die("Could not delete spec");

	$message = "Deleted specification: ".$spec["name"];
	$insert = $db->insertAdminLog( $_SESSION["employee"], $message, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], session_id(), "danger" );

	if ($insert) {
		echo 'success';
	} else {
		echo 'Deletion Failed. Please try again.';
	}
}



?>
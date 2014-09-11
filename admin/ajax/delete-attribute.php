<?php 

spl_autoload_register(function ($class) {
	require_once "../../lib/classes/".$class.".class.php";
});

if(!isset($_SESSION)) {
	session_start();
}

if (isset($_POST["id"]) && isset($_SESSION["employee"])) {
	$db = new Database;

	// $db->query("SELECT name FROM attributes WHERE id = :id LIMIT 1");
	// $db->bind(':id', $_POST["id"]);
	// $spec = $db->single();
	// $spec or die("No attributes name");

	$db->query("DELETE FROM product_specs WHERE attribute_id = :attribute_id");
	$db->bind(':attribute_id', $_POST["id"]);
	$db->execute() or die("Could not delete product attribute");

	$db->query("DELETE FROM attributes WHERE id = :id LIMIT 1");
	$db->bind(':id', $_POST["id"]);
	$db->execute() or die("Could not delete attribute");

	$message = "Deleted attribute: ".$spec["name"];
	$insert = $db->insertAdminLog( $_SESSION["employee"], $message, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], session_id(), "danger" );

	if ($insert) {
		echo 'success';
	} else {
		echo 'Deletion Failed. Please try again.';
	}
}



?>
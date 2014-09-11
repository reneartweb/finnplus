<?php 

spl_autoload_register(function ($class) {
	require_once "../../lib/classes/".$class.".class.php";
});

if(!isset($_SESSION)) {
	session_start();
}

if (isset($_POST["id"]) && isset($_SESSION["employee"])) {
	$db = new Database;
	$db->query("UPDATE specs SET 
				name = :name ,
				name_nor = :name_nor ,
				slug = :slug ,
				count = :count
			 	WHERE id = :id ");

	$db->bind(':name', $_POST["name"]);
	$db->bind(':name_nor', $_POST["name_nor"]);
	$db->bind(':slug', Product::slugify($_POST["name"]) );
	$db->bind(':count', $_POST["count"]);
	$db->bind(':id', $_POST["id"]);
	$update = $db->execute();

	$message = "Changed specification ".$_POST["id"]." information to:";
	$message .= "name = ".$_POST["name"];
	$message .= "name_nor = ".$_POST["name_nor"];	
	$message .= ", count = ".$_POST["count"];

	$insert = $db->insertAdminLog( $_SESSION["employee"], $message, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], session_id() );

	if ($update && $insert) {
		echo 'success';
	} else {
		echo 'Change Failed. Please try again.';
	}
}



?>
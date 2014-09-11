<?php 

spl_autoload_register(function ($class) {
	require_once "../../lib/classes/".$class.".class.php";
});

if(!isset($_SESSION)) {
	session_start();
}

if (isset($_POST["id"]) && isset($_POST["merge_id"]) && isset($_SESSION["employee"])) {
	$db = new Database;
	$db->query("UPDATE product_specs SET attribute_id = :attribute_id WHERE attribute_id = :merge_id ");
	$db->bind(':attribute_id', $_POST["id"]);
	$db->bind(':merge_id', $_POST["merge_id"]);
	$update = $db->execute();

	$db->query("SELECT count FROM attributes WHERE id = :merge_id LIMIT 1");
	$db->bind(':merge_id', $_POST["merge_id"]);
	$merge_spec = $db->single();

	$db->query("UPDATE attributes SET count = count+:merge_count WHERE id = :attribute_id LIMIT 1");
	$db->bind(':attribute_id', $_POST["id"]);
	$db->bind(':merge_count', $merge_spec["count"] );
	$update_count = $db->execute();

	$db->query("DELETE FROM attributes WHERE id = :merge_id LIMIT 1");
	$db->bind(':merge_id', $_POST["merge_id"]);
	$delete = $db->execute();

	$message = "Merged Attribute ".$_POST["id"]." and ".$_POST["merge_id"].". Deleted attr ".$_POST["merge_id"];
	$insert = $db->insertAdminLog( $_SESSION["employee"], $message, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], session_id(), "warning" );

	if (!$update) {
		echo 'update failed';
	} elseif (!$update_count) {
		echo 'update_count failed';
	} elseif (!$delete) {
		echo 'delete failed';
	} elseif (!$insert) {
		echo 'insert failed: '.$insert.", browser:".$_SERVER['HTTP_USER_AGENT'].", id:".$_SERVER['REMOTE_ADDR'];
	} else {
		echo 'success';
	}
}



?>
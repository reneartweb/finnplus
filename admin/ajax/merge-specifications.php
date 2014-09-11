<?php 

spl_autoload_register(function ($class) {
	require_once "../../lib/classes/".$class.".class.php";
});

if(!isset($_SESSION)) {
	session_start();
}

if (isset($_POST["id"]) && isset($_POST["merge_id"]) && isset($_SESSION["employee"])) {
	$db = new Database;
	$db->query("UPDATE product_specs SET spec_id = :spec_id WHERE spec_id = :merge_id ");
	$db->bind(':spec_id', $_POST["id"]);
	$db->bind(':merge_id', $_POST["merge_id"]);
	$update = $db->execute();

	$db->query("SELECT count FROM specs WHERE id = :merge_id LIMIT 1");
	$db->bind(':merge_id', $_POST["merge_id"]);
	$merge_spec = $db->single();

	$db->query("UPDATE specs SET count = count+:merge_count WHERE id = :spec_id LIMIT 1");
	$db->bind(':spec_id', $_POST["id"]);
	$db->bind(':merge_count', $merge_spec["count"] );
	$update_count = $db->execute();

	$db->query("DELETE FROM specs WHERE id = :merge_id LIMIT 1");
	$db->bind(':merge_id', $_POST["merge_id"]);
	$delete = $db->execute();

	$message = "Merged Specs ".$_POST["id"]." and ".$_POST["merge_id"].". Deleted spec ".$_POST["merge_id"];
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
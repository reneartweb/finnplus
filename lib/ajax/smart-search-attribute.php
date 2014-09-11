<?php 
	if (!$_SESSION) session_start();

	spl_autoload_register(function ($class) { require_once "../classes/".$class.".class.php"; });

	$db = new Database();

	if (empty($_POST["attribute"])) {
		die();
	}

	$lang = "eng";
	if (isset($_SESSION["lang"])) {
		$lang = $_SESSION["lang"];
	}

	$attribute = $_POST["attribute"];

	// set the character set so it will also display forgein characters and not diamant question marks
	header("Content-type: text/html; charset=iso-8859-1");

	$db->smartSearchAttribute($attribute, $lang);

?>
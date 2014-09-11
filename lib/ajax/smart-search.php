<?php 

	require_once "../includes/sanitize-all.php";
	require_once "../includes/session.php";

	spl_autoload_register(function ($class) { require_once "../classes/".$class.".class.php"; });

	$db = new Database();

	if (empty($_POST["spec"])) {
		die();
	}

	$spec = $_POST["spec"];

	// set the character set so it will also display forgein characters and not diamant question marks
	header("Content-type: text/html; charset=iso-8859-1");

	if (!empty($_POST["attribute"])) {
		$attribute = $_POST["attribute"];
		$db->smartSearch($spec, $attribute, $_SESSION["lang"]);
	} else {
		$db->smartSearch($spec, "", $_SESSION["lang"]);
	}

?>
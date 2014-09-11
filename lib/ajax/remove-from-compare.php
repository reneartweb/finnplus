<?php 

	$_POST["advert_id"] or die('Restricted access'); // Security to prevent direct access to php files.
	spl_autoload_register(function ($class) { require_once "../classes/".$class.".class.php"; });
	require_once "../includes/sanitize-all.php";
	require_once "../includes/session.php";

	$id = (int) $_POST["advert_id"];
	unset($_SESSION["compare"][$id]);
 ?>
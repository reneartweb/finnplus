<?php 

	require_once "../includes/session.php";
	require_once "../includes/sanitize-all.php";

	spl_autoload_register(function ($class) { require_once "../classes/".$class.".class.php"; });

	if ( empty($_GET["search"]) ) {
		die();
	}

	$search_string = $_GET["search"];
	$sub_category_id = $_GET["category"];

	// set the character set so it will also display forgein characters and not diamant question marks
	// header("Content-type: text/html; charset=iso-8859-1");

	$products = Product::searchAdvertisment($sub_category_id, $search_string);

	if ($products) {
		require_once("../includes/advert-item.php");
	} else { ?>
		<h2>No advertisments found</h2>
	<?php }

?>

<script src="lib/js/results-list.js"></script>
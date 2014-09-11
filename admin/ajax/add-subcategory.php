<?php 

spl_autoload_register(function ($class) {
	require_once "../../lib/classes/".$class.".class.php";
});

if(!isset($_SESSION)) {
	session_start();
}

if (isset($_POST["name"]) && isset($_SESSION["employee"]) ) {
	$db = new Database;
	$db->query("INSERT INTO categories_sub ( name, main_cat_id, 10_day_price_nok, 20_day_price_nok, 30_day_price_nok, top_add_price_nok, video_price_nok, bold_view_price_nok, top_search_price_nok) 
									VALUES (:name,:main_cat_id,:10_day_price_nok,:20_day_price_nok,:30_day_price_nok,:top_add_price_nok,:video_price_nok,:bold_view_price_nok,:top_search_price_nok)");

	$db->bind(':name',				  $_POST["name"]);
	$db->bind(':main_cat_id', 		  $_POST["main_cat_id"]);
	$db->bind(':10_day_price_nok', 	  $_POST["10_day_price_nok"]);
	$db->bind(':20_day_price_nok', 	  $_POST["20_day_price_nok"]);
	$db->bind(':30_day_price_nok', 	  $_POST["30_day_price_nok"]);
	$db->bind(':top_add_price_nok',   $_POST["top_add_price_nok"]);
	$db->bind(':video_price_nok', 	  $_POST["video_price_nok"]);
	$db->bind(':bold_view_price_nok', $_POST["bold_view_price_nok"]);
	$db->bind(':top_search_price_nok',$_POST["top_search_price_nok"]);

	$db->insertAdminLog( $_SESSION["employee"], "Added subcategory ".$_POST["name"], $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], session_id() );

	if ($db->execute()) {
		echo 'success';
	} else {
		echo 'Adding new category failed. Please try again.';
	}
}



?>
<?php 

	require_once "../includes/session.php";
	require_once "../includes/sanitize-all.php";
	// Auto load the class when it is beeing created
	spl_autoload_register(function ($class) {
		require_once "../classes/".$class.".class.php";
	});
	if ( !empty($_POST["product_id"]) && !empty($_POST["action"])  ) {

		$insert = Activity::saveToDB($_POST['product_id'], $_POST['action']);
		if ($insert) {
			echo "success";
		} else {
			echo "failure 2";
		}
	} else {
		echo "failure 1";
	}
?>
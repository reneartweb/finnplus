<?php 

class Activity { 

	// Constructor
	public function __construct() {
	}

	// Methods
	public function saveToDB($product_id, $action) { 
		if (!$_SESSION) session_start();

		if (isset($_SESSION['user_id'])) {
			$user_id = $_SESSION['user_id'];
		} else {
			$user_id = null;
		}

		$database = new Database();
		$database->query('INSERT INTO activity_statistics ( product_id, action, ip_address, browser, user_id)
												   VALUES (:product_id,:action,:ip_address,:browser,:user_id)');

		$database->bind(':product_id', $product_id);
		$database->bind(':action', $action);
		$database->bind(':ip_address', $_SERVER['REMOTE_ADDR']);
		$database->bind(':browser', $_SERVER['HTTP_USER_AGENT']);
		$database->bind(':user_id', $user_id);

		if ($database->execute()) {
			return true;
		}

		return false;
	}

	// abstract public function display(); // this means that it forces all the children classes to have a display function
}

?>
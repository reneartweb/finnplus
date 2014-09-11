<?php 
	if (!$_SESSION) session_start();

	$_POST["user_id"] or die('Restricted access'); // Security to prevent direct access to php files.
	spl_autoload_register(function ($class) { require_once "../classes/".$class.".class.php"; });
	require_once "../includes/sanitize-all.php";

	$db = new Database();
	$db->query("SELECT name, email, phone FROM users WHERE id = :user_id LIMIT 1");
	$db->bind(":user_id", $_POST["user_id"] );
	$user_info = $db->single();

	// $alert_modal_id = "contact-seller-modal";
	// $title = "Seller Information";
	// $footer = "";

	//ob_start(); // Start recording the content for the modal ?>
		<img style="float:left; padding-right: 10px;" src="https://secure.gravatar.com/avatar/<?php echo md5( strtolower( trim( $user_info["email"] ) ) ); ?>?d=mm&s=75" alt="<?php echo $user_info["name"]; ?> profile picture">
		<p><em><?php echo Translate::string("contact_seller_modal.name"); ?></em> <?php echo $user_info["name"]; ?></p>
		<p><em><?php echo Translate::string("contact_seller_modal.email"); ?></em> <a href="mailto:<?php echo $user_info["email"]; ?>"><?php echo $user_info["email"]; ?></a></p>
		<p><em><?php echo Translate::string("contact_seller_modal.phone"); ?></em> <a href="tel:<?php echo $user_info["phone"]; ?>"><?php echo $user_info["phone"]; ?></a></p>
		<?php
	// 	$modal_content = ob_get_contents();
	// ob_end_clean(); // end recording

	// echo $modal_content;

	// get the modal
	// DocElement::modal($alert_modal_id, $title, $modal_content, $footer);
		
?>
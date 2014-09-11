<?php 

	define('ALLOW_ACCESS', true); // allow access to this page
	// defined('ALLOW_ACCESS') or die('Restricted access'); 	// Security to prevent direct access to php files.

	$title = "finnplus.no";
	$meta_description = ""; // optimal meta description lenght 150-160 character
	
	require_once("lib/includes/header.php");
	require_once("lib/includes/reset-password-modal.php");
	
	// important translations


	?>
	<script>
		var error_adblock = "<?php echo Translate::string('error.ad_block') ?>";
		var error_slow_internet = "<?php echo Translate::string('error_slow_internet') ?>";
		var error_slow_internet_title = "<?php echo Translate::string('error_slow_internet_title') ?>";
		var alert_seller_info_title = "<?php echo Translate::string('alert_seller_info_title') ?>";
		var form_view_all_elements = "<?php echo Translate::string('form.view_all_elements') ?>";
		var results_list_title = "<?php echo Translate::string('results_list_title') ?>";
		var alert_reset_password_success = "<?php echo Translate::string('alert_reset_password_success') ?>";
		var save_advertisement_max_imagex = "<?php echo Translate::string('save_advertisement_max_imagex') ?>";
	</script>
	<?php

	require_once("lib/includes/categories.php");
	require_once("lib/includes/compare.php");
	?><section id="top-ads"><?php
		require_once("lib/includes/top-ads.php");
	?></section><?php
	require_once("lib/includes/results.php");

	require_once("lib/includes/create-advert/advert-intro.php");
	require_once("lib/includes/create-advert/advert-header.php");
	require_once("lib/includes/create-advert/advert-create.php");

	require_once("lib/includes/footer.php"); 

?>
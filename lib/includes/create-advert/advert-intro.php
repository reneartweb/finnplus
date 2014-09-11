<?php 
	if (!$_SESSION) session_start();
	// define('ALLOW_ACCESS', true); // allow access to this page
	defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.
 ?>
<section id="advert-intro">
	<div class="container">
		<h2><?php echo Translate::string("create_ad.main_title"); ?></h2>
		<h3><?php echo Translate::string("create_ad.main_sub_title"); ?></h3>
	</div>
</section><?php #advert-intro ?>

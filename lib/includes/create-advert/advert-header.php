<?php 
	if (!$_SESSION) session_start();
	// define('ALLOW_ACCESS', true); // allow access to this page
	defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.
 ?>
<section id="advert-header">
	<div class="container">
		<h2 class="center-text"><?php echo Translate::string("create_ad.3_easy_steps"); ?></h2>
		<div class="create-step-wrap">
			<div id="create-step-frame"></div>
			<div id="create-step-line"></div>
			<?php //step header ?>
			<div id="create-merhandise" class="create-step">
				<p><?php echo Translate::string("create_ad.step"); ?></p>
				<span class="step-number step-active">1</span>
				<div class="create-symbol"></div><?php // specify the appropriate image like this: " #create-merchandise > .create-symbol {background...} "  ?>
				<h3><?php echo Translate::string("create_ad.step1_title"); ?></h3>
			</div>
			<?php //step header ?>
			<div id="create-validate" class="create-step">
				<p><?php echo Translate::string("create_ad.step"); ?></p>
				<span class="step-number">2</span>
				<div class="create-symbol"></div><?php // specify the appropriate image like this: " #create-merchandise > .create-symbol {background...} "  ?>
				<h3><?php echo Translate::string("create_ad.step2_title"); ?></h3>
			</div>
			<?php //step header ?>
			<div id="create-release" class="create-step">
				<p><?php echo Translate::string("create_ad.step"); ?></p>
				<span class="step-number">3</span>
				<div class="create-symbol"></div><?php // specify the appropriate image like this: " #create-merchandise > .create-symbol {background...} "  ?>
				<h3><?php echo Translate::string("create_ad.step3_title"); ?></h3>
			</div>
		</div>
	</div>
</section><?php // #advert-header  ?>
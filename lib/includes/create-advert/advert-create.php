<?php 
	if (!$_SESSION) session_start();
	// define('ALLOW_ACCESS', true); // allow access to this page
	defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.
	$_SESSION["upload_img_count"] = 0;
 ?>
	<section id="advert-create">
		<div class="container">
			<?php if (!$isLoggedIn): ?>
				<a href="?login#login" class="btn fullwidth"><?php echo Translate::string("create_ad_form.login_to_proceed"); ?></a>
				<a href="?register-modal" class="btn modal-btn fullwidth" style="margin-top:10px;"><?php echo Translate::string("create_ad.or_register"); ?></a>
			<?php else: ?>
				<div id="step-1" class="">
					<p><?php echo Translate::string("create_ad.instruction"); ?></p>
					<form id="create-form" action="lib/ajax/save-advertisment.php" method="post" enctype="multipart/form-data" role="form">
						<input type="checkbox" id="step-1a-checkbox" class="hidden checkbox-toggle">
						<div id="step-1a">
							<?php require_once("form-steps/step-1a.php"); ?>
						</div>
						<input type="checkbox" id="step-1b-checkbox" class="hidden checkbox-toggle" >
						<div id="step-1b" class="hidden fullwidth">
							<?php require_once("form-steps/step-1b.php"); ?>
						</div>
					</form>
					<div id="advert-img-form" class="hidden left">
						<div class="form-element one-liner">
							<label for="upload_files"><?php echo Translate::string("create_ad_form.upload_photos"); ?> *</label>
							<div id="upload_form" class="hidden">
								<form action="lib/ajax/upload-multiple-images.php" target="hidden_iframe" enctype="multipart/form-data" method="post">
									<input type="file" multiple accept="image/*" name="upload_files[]" id="upload_files">
								</form>
							</div>
							<button type="button" onclick="Uploader.upload();" class="btn btn-primary btn-lg"><?php echo sprintf(Translate::string("create_ad_form.choose_photos"), 10) ?></button>
							<iframe name="hidden_iframe" id="hidden_iframe" class="hidden"></iframe>
							<div id="uploaded_images"></div>
						</div>
					</div>
				</div>

				<div id="step-2" class="hidden">
					<h1><?php echo Translate::string("create_ad.preview_of_your_advertisement"); ?></h1>
					<div id="preview-container">
						<?php // include("lib/ajax/advertisement-expanded.php"); ?>
					</div>
					<button class="fullwidth" style="margin:60px 0 10px 0;"><?php echo Translate::string("create_ad.continue_to_next_step"); ?></button>
					<button class="fullwidth" ><?php echo Translate::string("create_ad.make_changes"); ?></button>
				</div>
				<div id="step-3" class="hidden"></div>
			<?php endif ?>
		</div>
	</section><?php // #advert-create  ?>
<?php 
	if (!$_SESSION) session_start();
	
	// define('ALLOW_ACCESS', true); // allow access to this page
	// defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.
	$load = false;
	$cat_id = 0;

	if ($_POST["top_ad_page"]) {
		spl_autoload_register(function ($class) { require_once "../classes/".$class.".class.php"; });
		require_once "../includes/sanitize-all.php";
		require_once "../includes/session.php";

		$top_ad_page = $_POST["top_ad_page"];
		$categoryID = $_POST["categoryID"];
		$hidden = "hidden";
	} else {
		$hidden = "";
		$top_ad_page = 0;
		$categoryID = "%";
	}

 ?>
	<div class="container">
		<h2><?php echo Translate::string("top_ads.title"); ?></h2>
		<!--ajax-->
		<div class="top-ad-container">
			<div id="top-ad-pages">
				<div class="top-ad-page<?php echo $top_ad_page; ?> top-ad-page <?php echo $hidden; ?> currentTopAdPage" data-top-ad-page-id="<?php echo $top_ad_page; ?>">
					<?php Product::getTopAds($categoryID, $top_ad_page); ?>
					<div class="row-break"></div>
				</div>
			</div>

			<div id="top-ad-controls">
				<button type="button" id="top-ad-control-left" class="top-ad-pager" data-direction="prev" data-current-page="<?php echo $top_ad_page; ?>" ></button>
				<button type="button" id="top-ad-control-right" class="top-ad-pager" data-direction="next" data-current-page="<?php echo $top_ad_page; ?>" ></button>
			</div>
		</div>
	</div> <?php // End of .container  ?>
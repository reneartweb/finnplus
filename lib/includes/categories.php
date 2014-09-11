<?php 
	if (!$_SESSION) session_start();
	
	// define('ALLOW_ACCESS', true); // allow access to this page
	defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.
	$load = false;
	$cat_id = 0;
 ?>
<section id="categories">
	<div class="container" role="navigation" aria-label="All Categories">
		<h2><?php echo Translate::string("categories.title"); ?></h2>
		<?php if ( isset($_GET["cat_id"]) && !empty($_GET["cat_id"]) && is_numeric($_GET["cat_id"]) ) {
			$load = true;
			$cat_id = $_GET["cat_id"]; ?>
			<script>
				$(document).ready(function() {
					resizeSubCatContainer(".sub-category");
				});
			</script>
		<?php } ?>
		<a href="?" class="category category-back-btn column <?php echo ($load)? "" : "hidden"; ?>">
			<span><?php echo Translate::string("categories.back_btn"); ?></span>
		</a>
		
		<?php foreach ($mainCategories as $cat): ?>
			<?php if ($cat["id"] == 0) continue; // skip deleted categories ?>
			<div class="category column <?php echo ($load && $cat['id']!=$cat_id)? "hidden" : ""; ?>">
				<a data-id="<?php echo $cat['id']; ?>" href="?cat_id=<?php echo $cat['id']; ?>">
					<div class="category-thumb">
						<img src="lib/images/main-categories/id/<?php echo $cat['id']; ?>.jpg" alt="<?php echo Translate::string("categoryMain.".Product::slugify($cat["name"])); ?> Category">						
					</div>
					<span class="category-label"><?php echo Translate::string("categoryMain.".Product::slugify($cat["name"])); ?></span>
				</a>
			</div>
		<?php endforeach ?>

		<div id="sub-category-container" class="<?php echo ($load)? "" : "hidden"; ?>">
			<?php if ($load) {
				require_once("lib/ajax/getSubCategories.php");
			} ?>
		</div>
	</div> <?php // End of .container  ?>
</section><?php // End of #categories ?>
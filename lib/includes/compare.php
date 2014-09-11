<?php if (!$_SESSION) session_start(); ?>
<?php # checkbox needs to be here! manipulated with css, if clicked, compare section is hidden ?>		
<input id="compare-checkbox" type="checkbox" class="hidden" <?php echo (isset($_GET["compare"])) ? '' : 'checked="checked"' ; ?> >
<section id="compare">
	<div class="container">
		<h2><?php echo sprintf(Translate::string("compare.title"), '<span id="compare-title-count">'.count($_SESSION["compare"]).'</span>'); ?></h2>
		<div class="table">
			<div class="compare-header row">
				<div class="cell"></div>
				<div class="cell"><a class="compare-feature">ID</a></div>
				<div class="cell"><a class="compare-feature">Title</a></div>
				<div class="cell"><a class="compare-feature">Price</a></div>
				<div class="cell"><a class="compare-feature">Location</a></div>
				<div class="cell"><a class="compare-feature ">Date Created</a></div>
				<div class="cell"><a href="#"></a></div>
			</div>

			<?php if (isset($_SESSION["compare"]) && count($_SESSION["compare"]) > 1): ?>
				<?php foreach ($_SESSION["compare"] as $product_id): ?>
					<?php Product::getCompareItem($product_id); ?>
				<?php endforeach ?>
			<?php endif ?>
			<!-- 
				<div class="compare-item row">
					<div class="cell"><img alt="test image" src="lib/images/main-categories/id/2.jpg"></div>
					<div class="cell">Manchester</div>
					<div class="cell">48 M<sup>2</sup></div>
					<div class="cell">1 rooms</div>
					<div class="cell">concrete</div>
					<div class="cell">2006</div>
					<div class="cell">convector</div>
					<div class="cell">27.100 EUR</div>
					<div class="cell"><a href="" class="remove-link">Remove</a></div>
				</div> 
			-->
		</div>
	</div>
</section>
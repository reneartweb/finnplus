<?php 
	if (!$_SESSION) session_start();
	$db = New Database;
	foreach ($products as $p) { 

	$db->query('SELECT uuid FROM product_images WHERE product_id = :product_id ORDER BY id ASC LIMIT 1 ');
	$db->bind(':product_id', $p["id"]);
	$product_image = $db->single();

	?>
		<a href="" class="ad<?php if ($p["top_add"]) echo " top_ad"; ?>" data-id="<?php echo $p["id"]; ?>">
			<figure>
				<img class="ad-img" src="lib/images/uploads/small/<?php echo $product_image["uuid"]; ?>" alt="<?php echo $p["title"]; ?> thumbnail image">
			</figure>
			<h4><?php echo $p["title"]; ?></h4>
			<div class="ad-details">
				<?php if ($subCatID == "%"): ?>
					<p class="ad-detail">
						<span class="ad-detail-label"><?php echo Translate::string("ad_item.category"); ?></span>
						<span class="ad-detail-text"><?php echo $p["sub_category"]; ?></span>
					</p>
				<?php endif ?>
				<p class="ad-detail">
					<span class="ad-detail-label"><?php echo Translate::string("ad_item.location"); ?></span>
					<span class="ad-detail-text"><?php echo $p["city_name"]; ?> (<?php echo $p["zip"]; ?>)</span>
				</p>
				<p class="ad-detail">
					<span class="ad-detail-label"><?php echo Translate::string("ad_item.created"); ?> </span>
					<span class="ad-detail-text"><?php echo strftime("%d. %B %Y", strtotime( $p["date_created"] )); ?></span>
				</p>
			<footer>
				<p class="price"><?php echo $p["price"]; ?> <?php echo $p["currency"]; ?></p>
				<div class="compare-icon" data-advert-id="<?php echo $p["id"]; ?>">&#9733;</div>
			</footer>
			</div>
			<?php if ($p["top_add"]): ?>
				<div class="top_ad_images">
				<?php 
					$db->query('SELECT uuid FROM product_images WHERE product_id = :product_id ORDER BY id ASC LIMIT 10 OFFSET 1');
					$db->bind(':product_id', $p["id"]);
					$product_images = $db->fetchAll();
					foreach ($product_images as $image) { ?>
						<img class="top-ad-img" src="lib/images/uploads/thumbnail/<?php echo $image["uuid"]; ?>" alt="<?php echo $p["title"]; ?> thumbnail image">
					<?php }
				 ?>
				 </div>
			<?php endif ?>
		</a>
<?php } ?>
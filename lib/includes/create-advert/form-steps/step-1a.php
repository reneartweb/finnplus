<?php 
	if (!$_SESSION) session_start();
	// define('ALLOW_ACCESS', true); // allow access to this page
	defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.
 ?>
					
						<noscript> <!-- if javascript is not enabled or not supported by the browser -->
							<section class="third left">
								<?php 

									$settings = array(
										"class" => "btn",
										"required" => true);

									$db->getSelectOfAllCategories( $settings ); 

								?>
							</section>
						</noscript>
		
						<noscript> <div class="hidden"> </noscript>
							<section id="create-main-cat" class="third left radio-switch">
								<h3><?php echo Translate::string("create_ad.select_main_category"); ?></h3>
								<?php // $mainCats = $db->getMainCategoriesArray("case when name = 'Free Stuff' then 2 else 1 end,name ASC"); ?>
								<ul class="unstyled">
									<!-- $mainCategories is already preloaded in the header.php file but in a name DESC order -->
									<?php 
										$mainCatID = 1;
										if (!empty($_GET["cat_id"])) {
											$mainCatID = $_GET["cat_id"];
										}
									?>
									<?php foreach ($mainCategories as $cat): ?> 
										<?php if ($cat["id"] == 0) continue; // skip deleted categories ?>
										<?php $status = ($cat["id"] == $mainCatID) ? "checked='checked'" : ""; ?>
										<li>
											<input <?php echo $status; ?> type="radio" name="main-category" value="<?php echo $cat["id"]; ?>" id="main-cat-<?php echo $cat["id"]; ?>">
											<label for="main-cat-<?php echo $cat["id"]; ?>"><a class="preventDefault" href="?cat_id=<?php echo $cat["id"]; ?>#advert-create"><?php echo Translate::string("categoryMain.".Product::slugify($cat["name"])); ?></a></label>
										</li>
									<?php endforeach ?>
								</ul>
							</section>

							<section id="create-sub-cat" class="third left radio-switch">
								<h3><?php echo Translate::string("create_ad.select_sub_category"); ?></h3>
								<ul class="unstyled">
									<?php include("lib/ajax/getSubCategoriesAsRadioList.php"); ?>
								</ul>
							</section>
						<noscript> </div> </noscript>

						<section id="create-basics" class="third left">
							<noscript>
								<h3><?php echo Translate::string("create_ad.continue_sentence"); ?></h3>
								<label for="step-1a-checkbox" class="btn left fullwidth"><?php echo Translate::string("create_ad.continue_button"); ?></label>
							</noscript>
						</section>
					
<?php 
	if (!$_SESSION) session_start();
	$_POST["product_id"] or die("Restricted area"); // Security to prevent direct access to php files.

	require_once "../includes/sanitize-all.php";

	// Auto load the class when it is beeing created
	spl_autoload_register(function ($class) {
		require_once "../classes/".$class.".class.php";
	});

	$product_id = 36;
	$update = false;

	if (isset($_POST["product_id"])) {
		$product_id = $_POST["product_id"];
		function dateString($date, $format = "%d. %B %Y") {
			return strftime($format, strtotime( $date ));
		}

	}

	if (isset($_POST["update"])) {
		$update = $_POST["update"];
	}

	$lang = "eng";
	$attribute_name = "attribute_name";
	$spec_name = "spec_name";
	setlocale(LC_ALL, "en_EN.UTF-8");
	
	if (isset($_SESSION["lang"])) {
		$lang = $_SESSION["lang"];
		if ($lang == "nor") {
			$attribute_name = "attribute_name_nor";
			$spec_name = "spec_name_nor";
			setlocale(LC_ALL, "no_NO.UTF-8");
		}
	}


	$db = new Database;
	$db->query('SELECT * FROM product_specs_view WHERE product_id = :product_id ORDER BY spec_count DESC');
	$db->bind(':product_id', $product_id);
	$product_specs = $db->fetchAll();

	$db->query('SELECT * FROM products_view WHERE id = :product_id LIMIT 1');
	$db->bind(':product_id', $product_id);
	$product_info = $db->single();

	$db->query('SELECT u.name, u.id, u.date_registered, u.email FROM users as u, products as p WHERE p.id = :product_id AND u.id = p.user_id LIMIT 1');
	$db->bind(':product_id', $product_id);
	$user_info = $db->single();

	$db->query('SELECT count(id) as count FROM products as p WHERE p.user_id = :user_id');
	$db->bind(':user_id', $user_info["id"]);
	$advert = $db->single();

	$db->query('SELECT * FROM product_images WHERE product_id = :product_id LIMIT 10');
	$db->bind(':product_id', $product_id);
	$product_images = $db->fetchAll();

	Activity::saveToDB($product_id, "click");

 ?>

<div class="ad-expanded"> <!-- // over all container for the ad -->
	<i class="arrow"></i>
	<div class="result_container" > <!-- // the blue result contain -->
		<?php echo ($update) ? "<form id='update-advertisment-form' action='lib/ajax/update-advertisment.php' method='POST'>" : ""; ?>
		<div class="result_content"> <!-- // the white result content contain -->
			<div class="result_details_container">
				<?php if (!$update): ?>
					<a class="close-ad-btn btn"><?php echo Translate::string("ad_expanded.close_btn"); ?></a>				
				<?php endif ?>
				<div class="result_details">
					<?php if ($update): ?>
						<input id="update-title" type="text" required name="title" value="<?php echo $product_info["title"]; ?>">
					<?php else: ?>
						<h2><?php echo $product_info["title"]; ?></h2>
					<?php endif ?>
					<?php if ($update): ?>
						<p>Change status to 
							<select class="btn btn-slim" name="status">
								<option value="">Published</option>
								<option value="">Draft</option>
							</select>
						</p>
					<?php else: ?>
						<p>Published on <?php echo dateString($product_info["date_created"]); ?></p>					
					<?php endif ?>

					 <!-- | <a title="By 10 Users" >Reported as sold (10)</a>-->
					 <span>
						<em>
					<?php if ($update): ?>
						<input type="text" style="width:60px;" required name="price" value="<?php echo $product_info["price"]; ?>">
						<select  class="btn btn-slim" name="currencyID">
						<?php $currencies = FormElement::getCurrencies();
							if ($currencies) {
								foreach ($currencies as $currency) { ?>
									<option <?php echo ($currency['currency'] == $product_info["currency"]) ? "selected" : ""; ?> value="<?php echo $currency['id'];?>"><?php echo $currency['currency']; ?></option>
								<?php }
							}
						 ?>
						</select>
					<?php else: ?>
						<?php echo $product_info["price"]." ".$product_info["currency"]; ?>
					<?php endif ?>
						</em>
					</span>
					<span>
						<?php if ($update): ?>
							<em>
								<input style="width:70px;" type="text" placeholder="City Name" name="city_name" value="<?php echo $product_info["city_name"]; ?>" required>
								<input style="width:40px;" type="text" placeholder="Postal Code" name="zip" value="<?php echo $product_info["zip"]; ?>" required>
								<select class="btn btn-slim" name="country_code">
									<option <?php echo ($product_info["country_code"] == "no") ? "selected" : "" ; ?> value="no">Norway</option>
									<option <?php echo ($product_info["country_code"] == "se") ? "selected" : "" ; ?> value="se">Sweden</option>
									<option <?php echo ($product_info["country_code"] == "dk") ? "selected" : "" ; ?> value="dk">Denmark</option>
								</select>
							</em>
						<?php else: ?>
							<em class="google-maps" data-location="<?php echo $product_info['zip']."%20".urlencode($product_info['city_name'])."%2C%20".$product_info['country_code']; ?>">
								<?php echo $product_info["city_name"]; ?>, <?php echo strtoupper($product_info["country_code"]); ?> (<?php echo $product_info["zip"]; ?>) <img src="lib/images/elements/20px-Google_Maps_application_icon.jpg" alt="Google Maps Icon">
							</em>
						<?php endif ?>
					</span>
					
					
					<span><?php echo Translate::string("ad_expanded.payment_in"); ?> <?php if ($update): ?>
						<select  class="btn btn-slim" name="paymentMethod">
							<?php 
								$methods = FormElement::getPaymentMethods();
								if ($methods) {
									foreach ($methods as $method) {
										echo '<option value="'.$method['id'].'">'.$method['method'].'</option>';
									}
								}
							 ?>
						</select>
						<?php else: ?>
							<?php echo $product_info["payment_method"]; ?>
						<?php endif ?>
					</span>
					<h3><?php echo Translate::string("ad_expanded.description"); ?></h3>
					<?php if ($update): ?>
						<p><textarea name="description" required cols="60" rows="10"><?php echo $product_info["description"]; ?></textarea></p>
					<?php else: ?>
						<?php if (strlen($product_info["description"]) > 421): ?>
							<input type="checkbox" id="read-more">
							<p><?php echo substr($product_info["description"], 0, 421); ?>... <label for="read-more"><?php echo Translate::string("ad_expanded.read_more"); ?></label></p>							
						<?php endif ?>
						<p><?php echo $product_info["description"]; ?></p>
					<?php endif ?>
					<h3><?php echo Translate::string("ad_expanded.details"); ?></h3>
					<div class="details_block <?php if (count($product_specs) <= 6 && count($product_specs) > 2) {echo "few-details";} elseif(count($product_specs) > 6) {echo "much-details";}?>">
					<?php 

						if ($product_specs) {
							$extra_details = array();
							$product_specs_iteration = 0;
							foreach ($product_specs as $key => $spec) {
								
								if (!$product_attribute_name = $spec[$attribute_name]) $product_attribute_name = $spec["attribute_name"];
								if (!$product_spec_name = $spec[$spec_name]) $product_spec_name = $spec["spec_name"];

								if ($spec["attribute_type"] == "checkbox") {
									// if the array does not exist then create it
									if (!isset( $extra_details[$product_attribute_name] )) $extra_details[$product_attribute_name] = array();
									// push elements to this array
									array_push($extra_details[$product_attribute_name], $product_spec_name);
								} else { ?>
									<?php if ($spec["attribute_slug"] == "registration-number" && $product_info["country_code"] == "no"): ?>
										<span><?php echo $product_attribute_name.":"; ?> <strong><a href="http://www.vegvesen.no/Kjoretoy/Eie+og+vedlikeholde/Kj%C3%B8ret%C3%B8yopplysninger?registreringsnummer=<?php echo urlencode($product_spec_name); ?>">Click to see info <?php // echo $product_spec_name; ?></a></strong></span>
									<?php elseif ($spec["attribute_slug"] == "registration-number" && $product_info["country_code"] == "dk"): ?>
										<span><?php echo $product_attribute_name.":"; ?> <strong><a href="http://www.nummerplade.net/soeg/?regnr=<?php echo urlencode($product_spec_name); ?>">Click to see info <?php // echo $product_spec_name; ?></a></strong></span>
									<?php else: ?>
										<?php if ($product_specs_iteration % 2 !== 1): ?>
											<div class="details_row">
										<?php endif ?>

										<span><?php echo $product_attribute_name.":</span><span><strong> ".$product_spec_name; ?></strong></span>

										<?php if (count($product_specs)-1 == ($product_specs_iteration) and $product_specs_iteration % 2 == 0): ?>
											<span></span><span></span>
										<?php endif ?>

										<?php if (count($product_specs)-1 == ($product_specs_iteration) or ((count($product_specs)-1 !== ($product_specs_iteration)) and $product_specs_iteration % 2 !== 0)): ?>
											</div>
										<?php endif ?>

										<?php $product_specs_iteration++; ?>
									<?php endif ?>
									<?php
								}
							}
						}

						if ($extra_details) {
							// Sort extra details by the number of specifications each attribute has
							array_multisort(array_map('count', $extra_details), SORT_ASC, $extra_details);
							$product_specs_num = $product_specs_iteration + count($extra_details);
							foreach ($extra_details as $key => $value) { ?>
								<?php 
									$specs_str = "";
									if (count($value) > 1) {
										$specs_str = "<ul style='margin-top:0;'>";
										foreach ($value as $spec) {
											$specs_str .= "<li>".$spec."</li>";
										}
										$specs_str .= "</ul>";
									} else {
										$specs_str = $value[0];
									}
								 ?>
								<?php if ($product_specs_iteration % 2 == 0): ?>
									<div class="details_row">
								<?php endif ?>

								<span><?php echo $key; ?>: </span><span><strong><?php echo $specs_str; ?></strong></span>

								<?php if ($product_specs_num-1 == ($product_specs_iteration) and $product_specs_num % 2 == 1): ?>
									<span></span><span></span>
								<?php endif ?>

								<?php if ($product_specs_num-1 == ($product_specs_iteration) or (($product_specs_num-1 !== ($product_specs_iteration)) and $product_specs_iteration % 2 !== 0)): ?>
									</div>
								<?php endif ?>

							<? $product_specs_iteration++; }
						}
					 ?>
					 </div>
				</div>
				
				<div class="expanded-author">
					<img src="https://secure.gravatar.com/avatar/<?php echo md5( strtolower( trim( $user_info["email"] ) ) ); ?>?d=mm&s=48" alt="advertisment author profile picture">
					<div>
						<h4><?php echo $user_info["name"]; ?></h4>
						<p><?php echo sprintf(Translate::string("ad_expanded.nr_of_deals_since_date_registred"), $advert["count"], dateString($user_info["date_registered"], "%B, %Y")); ?></p>
					</div>
				</div>
				<a class="btn contact-seller" data-user-id="<?php echo $user_info["id"]; ?>"><?php echo Translate::string("ad_expanded.contact_seller"); ?></a>
				<a data-advert-id="<?php echo $product_id; ?>" class="add-to-compare-btn btn"><?php echo Translate::string("ad_expanded.add_to_compare"); ?></a>
			</div>
			<aside class="gallery_container">
				<div class="result_gallery">
					<div class="img-navigation"><button class="prev-gallery-img"></button></div>
					<div class="img-navigation"><button class="next-gallery-img"></button></div>
					<img src="lib/images/uploads/medium/<?php echo $product_images[0]["uuid"]; ?>" data-id="<?php echo $product_images[0]["id"]; ?>" class="result_img" alt="advertisment gallery image">
				</div>
				
				<div class="img-gallery">
					<?php foreach ($product_images as $key=>$image): ?>
						<img src="lib/images/uploads/thumbnail/<?php echo $image['uuid']; ?>" data-id="<?php echo $image['id']; ?>" alt="advertisment gallery image">
						<?php if ($key == 4 or (count($product_images) == ($key+1) && count($product_images) < 6)) {
						?>

						<div id="GoogleMapThumbnail" class="google-maps" data-location="<?php echo $product_info['zip']."%20".urlencode($product_info['city_name'])."%2C%20".$product_info['country_code']; ?>">
							<img alt="GoogleMap" src="https://maps.google.com/maps/api/staticmap?center=<?php echo $product_info['zip']."%20".urlencode($product_info['city_name'])."%2C%20".$product_info['country_code']; ?>&zoom=12&size=165x165&sensor=false" >
						</div>

						<?php
						} if ($key == 9 or count($product_images) == ($key+1) ) {
						?>
							<img id="ad-video-thumb" src="http://rene-ollino.eu/project-banana/lib/images/uploads/thumbnail/video_placeholder.jpg" alt="video icon" title="Not available yet.">
						<?php
						}
						?>
					<?php endforeach ?>
					

					<?php if ($update && count($product_images) < 10): ?>
						<div id="add-images-upload-form" class="hidden">
							<form action="lib/ajax/upload-multiple-images.php" target="hidden_iframe" enctype="multipart/form-data" method="post">
								<input type="file" multiple name="upload_files[]" accept="image/*" id="upload_files">
							</form>
						</div>
						<button id="add-images" type="button" onclick="Uploader.upload();" class="btn btn-primary btn-lg">+</button>
						<iframe name="hidden_iframe" id="hidden_iframe" class="hidden"></iframe>
					<?php endif ?>
				</div>
			</aside>
		</div>
		<?php if ($update): ?>
			<button class="btn fullwidth" style="margin-top:30px;">Save Changes</button>
		</form>			
		<?php endif ?>
	</div>
</div>

<?php if (isset($_POST["product_id"])): ?>
	<script src="lib/js/expanded-view.js"></script>
<?php endif ?>
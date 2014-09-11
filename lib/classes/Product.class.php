<?php 

// abstract class means that the product class object can not be created directly, but all his children classes can be
class Product { 

	// Properties
	protected $_id;
	protected $_owner;
	protected $_subCategorie;
	protected $_title;
	protected $_price;
	protected $_currencyID;
	protected $_paymentMethod;
	protected $_zip;
	protected $_cityName;
	protected $_locationID;
	protected $_language;
	protected $_description;
	protected $_photos;
	protected $_details;


	// Constructor
	public function __construct(User $owner, $subCategorie, $title, $price, $currencyID, $paymentMethod, $zip, $cityName, $countryCode, $language, $description, $details, $photos) {
		try {
			$this->_owner = clone $owner;
			$this->setTitle($title);
			$this->setSubCategorie($subCategorie);
			$this->setPrice($price);
			$this->setPaymentMethod($paymentMethod);
			$this->setZip($zip);
			$this->setCityName($cityName);
			$this->setCountryCode($countryCode);
			$this->setCurrencyID($currencyID);
			$this->setLanguage($language);
			$this->setDescription($description);
			$this->setDetails($details);
			$this->setPhotos($photos);
		} catch (Exception $e) {
			echo 'Error1:' .$e->getMessage();
		}
	}

	// Getters & Setters
	public function ownerID() { return $this->_owner->id(); }

	public function id() { return $this->_id; }
	public function setID($id) { 
		if (is_numeric($id)) {
			$this->_id = (int) $id;
		} else {
			throw new Exception("14. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function subCategorieID() { return $this->_subCategorie; }
	public function setSubCategorie($subCategorie) { 
		if (is_numeric($subCategorie)) {
			$this->_subCategorie = (int) $subCategorie;
		} else {
			throw new Exception("13. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function title() { return $this->_title; }
	public function setTitle($title) {
		if (is_string($title)) {
			$this->_title = $title; 
		} else {
			throw new Exception("12. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function price() { return $this->_price; }
	public function setPrice($price) {
		$price = (int) str_replace(" ", "", $price);
		if (is_numeric($price)) {
			$this->_price = $price;
		} else {
			throw new Exception("11. Please enter a number for price.".$price, 1);
		}
	}

	public function paymentMethod() { return $this->_paymentMethod; }
	public function setPaymentMethod($paymentMethod) {
		if (is_string($paymentMethod)) {
			$this->_paymentMethod = $paymentMethod; 
		} else {
			throw new Exception("10. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function cityName() { return $this->_cityName; }
	public function setCityName($cityName) {
		if (is_string($cityName)) {
			$this->_cityName = $cityName; 
		} else {
			throw new Exception("9. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function zip() { return $this->_zip; }
	public function setZip($zip) {
		if (is_string($zip)) {
			$this->_zip = $zip;
		} else {
			throw new Exception("8. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function countryCode() { return $this->_countryCode; }
	public function setCountryCode($countryCode) {
		if (is_string($countryCode) && strlen($countryCode) <= 3 ) {
			$this->_countryCode = $countryCode;
		} else {
			throw new Exception("7. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function currencyID() { return $this->_currencyID; }
	public function setCurrencyID($currencyID) {
		if (is_numeric($currencyID)) {
			$this->_currencyID = (int) $currencyID;
		} else {
			throw new Exception("6. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function locationID() { return $this->_locationID; }
	public function setLocationID($locationID) {
		if (is_numeric($locationID)) {
			$this->_locationID = (int) $locationID;
		} else {
			throw new Exception("5. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function language() { return $this->_language; }
	public function setLanguage($language) {
		if (is_string($language)) {
			$this->_language = $language; 
		} else {
			throw new Exception("4. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function description() { return $this->_description; }
	public function setDescription($description) {
		if (is_string($description)) {
			$this->_description = $description; 
		} else {
			throw new Exception("3. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function photos() { return $this->_photos; }
	public function setPhotos($photos) {
		if (is_array($photos)) {
			$this->_photos = $photos; 
		} else {
			throw new Exception("2. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function details() { return $this->_details; }
	public function setDetails($details) {
		if (is_array($details)) {
			ksort($details); // sort the array
			$this->_details = array_filter($details); // filter out empty array values
		} else {
			throw new Exception("1. Sorry, something has gone wrong. Please try again.", 1);
		}
	}

	public function getAttributes($sub_cat_id = 1, $status = "include", $limit = 10, $order = "ORDER BY count DESC, type ASC, name DESC ")
	{

		$lang = "eng";
		$attribute_name = "name";
		if (isset($_SESSION["lang"])) {
			$lang = $_SESSION["lang"];
			if ($lang == "nor") {
				$attribute_name = "name_nor";
			}
		}	

		$type_query = "";
		if ($status == "exclude-checkboxes" ) $type_query = ' AND type != "checkbox" AND type != "radio" ';
		if ($status == "only-checkboxes" ) $type_query = ' AND (type = "checkbox" OR type = "radio")';

		$database = new Database();
		$database->query('SELECT * FROM attributes WHERE sub_cat_id=:sub_cat_id '.$type_query.$order);
		$database->bind( ':sub_cat_id', $sub_cat_id );
		$attributes = $database->fetchAll();
		$i = 0;
		if ($attributes) {
			foreach ($attributes as $attribute) {
				$i++;
				$hidden = ($i > $limit) ? " hide-input-element" : "" ;
				$type = $attribute["type"];
				$list = array();
				$smart = ($type == "text") ? " smart-search" : "";
				if ($type == "checkbox" or $type == "radio") $list = self::getAtributeValues($attribute["id"]);

				if (!$product_attribute_name = $attribute[$attribute_name]) $product_attribute_name = $attribute["name"];
				
				FormElement::input(array('id' => "attribute-".$attribute["id"],
										'name' => $attribute["id"],
										'label' => $product_attribute_name,
										'class' => "one-liner".$smart.$hidden,
										'type' => $type,
										'more' => true,
										 ), $list);
			}
			if ($i > $limit) {
				echo '<button type="button" class="view-all-details-btn fullwidth">'.Translate::string("save_advertisement.view_all_attributes").'</button>';
			}
		} else {
			echo "<p>".Translate::string("save_advertisement.no_sub_category_info")."</p>";
		} 
		if ($status == "only-checkboxes") { ?>

			<div class="form-element checkbox one-liner hidden">
				<span><input type="text" class="detail-checkbox-label" placeholder="<?php echo Translate::string("form.detail_label"); ?>" name="detail-checkbox-label[0]"></span>
				<div class="form-element-wrap">
					<div class="add-more-checkbox-wrap">
						<input type="checkbox" checked="true"><label></label>
						<input autocomplete="off" type="text" name="detail-checkbox-value[0]" class="add-more-checkbox-input">
						<div title="<?php echo Translate::string("form.detail_remove"); ?>" class="remove-checkbox-input">X</div>
					</div>
					<div class="add-more-checkbox-wrap hidden">
						<input type="checkbox" checked="true"><label></label>
						<input autocomplete="off" type="text" name="detail-checkbox-value[0]" class="add-more-checkbox-input">
						<div title="<?php echo Translate::string("form.detail_remove"); ?>" class="remove-checkbox-input">X</div>
					</div>
					<a href="?more" class="btn add-more-checkbox-btn"><?php echo Translate::string("save_advertisement.add_more_btn"); ?></a>
				</div>
			</div>
		<?php } else { ?> 
			<div class="form-element one-liner smart-search new-info hidden">
				<input placeholder="<?php echo Translate::string("form.detail_label"); ?>" name="detail-label[]" type="text" class="smart-search-attribute">
				<input placeholder="<?php echo Translate::string("form.detail_info"); ?>"  name="detail-info[]"  type="text" >
				<div class="remove-more-derails-input">X</div>
			</div>
		<?php } ?>
		
		<button type="button" class="<?php echo ($i > $limit) ? " hide-input-element" : "" ; ?> add-more-details-btn fullwidth"><?php echo Translate::string("save_advertisement.add_more_details_btn"); ?></button> <?php
	}

	public function getAtributeValues($attributeID, $order = "ORDER BY specs.count DESC, specs.name ASC")
	{
		$database = new Database();
		$database->query('SELECT DISTINCT specs.id, specs.name, specs.name_nor, specs.slug FROM specs, product_specs WHERE product_specs.attribute_id=:attributeID AND product_specs.spec_id=specs.id '.$order);
		$database->bind( ':attributeID', $attributeID );
		$results = $database->fetchAll();
		return $results;
	}


	public function insertToDB()
	{
		try {
			
			$TimeStart = microtime(true); // to mesaure the time this method takes
			$database = new Database();
			$database->beginTransaction();

			$zip = $this->zip();
			$cityName = $this->cityName();
			$cityName = htmlentities($cityName, ENT_COMPAT, 'UTF-8');
			$countryCode = $this->countryCode();

			$title = $this->title();
			$price = $this->price();
			$photos = $this->photos();
			$details = $this->details();
			$ownerID = $this->ownerID();
			$language = $this->language();
			$currencyID = $this->currencyID();
			$description = $this->description();
			$paymentMethod = $this->paymentMethod();
			$subCategorieID = $this->subCategorieID();

			// make sure if the new spec is actually new or maybe it already exist in the database
			$database->query('SELECT id FROM locations WHERE LOWER(city_name) = :cityName LIMIT 1');
			$database->bind(':cityName', strtolower($cityName) );
			$location_row = $database->single();

			if ($location_row) {
				$locationID = $location_row["id"];
			} else {
				$database->query('INSERT INTO locations (city_name, zip, country_code) VALUES (:city_name, :zip, :country_code)');
				$database->bind(':city_name', $cityName);
				$database->bind(':zip', $zip);
				$database->bind(':country_code', $countryCode);
				
				if (!$database->execute()) 
					throw new Exception(Translate::string("save_advertisement.error_saving_location"), 1);

				$locationID = $database->lastInsertId();
			}

			$database->query('INSERT INTO products ( user_id, sub_cat_id, title, price, currency_id, location_id, payment_method_id, description, lang_id, date_created)
											VALUES (:user_id,:sub_cat_id,:title,:price,:currency_id,:location_id,:payment_method_id,:description,:lang_id,:date_created)');
			$database->bind(':user_id',$ownerID );
			$database->bind(':sub_cat_id',$subCategorieID );
			$database->bind(':title',$title );
			$database->bind(':price',$price );
			$database->bind(':currency_id', $currencyID );
			$database->bind(':location_id',$locationID );
			$database->bind(':payment_method_id', $paymentMethod );
			$database->bind(':description',$description );
			$database->bind(':lang_id', $language );
			$database->bind(':date_created',date("Y-m-d") );

			if ( $database->execute() ) {
				$productID = $database->lastInsertId();
				$this->setID($productID);
			} else {
				throw new Exception(Translate::string("save_advertisement.error_saving_advertisement"), 1);
			}

			if (count($details) > 0) {
				// ===============================================================================================
				// ================================= HANDELING THE EXTRA DETAILS =================================
				// ===============================================================================================
				if (isset($details["detail-label"]) && isset($details["detail-info"]) )
					$this->handleExtras($details, "detail-label", "detail-info", $database, $subCategorieID, $productID);

				if (isset($details["detail-checkbox-label"]) && isset($details["detail-checkbox-value"]) )
					$this->handleExtras($details, "detail-checkbox-label", "detail-checkbox-value", $database, $subCategorieID, $productID);

				// ================================================================================================
				// ================================= HANDELING THE NORMAL DETAILS =================================
				// ================================================================================================

				$extra_details = array("detail-label","detail-info","detail-checkbox-label","detail-checkbox-value");
				foreach ($details as $attribute_ID => $value) {
						// skip extra details
						if (in_array($attribute_ID, $extra_details)) continue;
						// increase the count
						$database->query('UPDATE attributes SET count = count+1 WHERE id = :id LIMIT 1');
						$database->bind(':id', $attribute_ID);
						if (!$database->execute()) throw new Exception("The Attribute does not exist", 1);
						$this->insertSpecs($value,$productID,$attribute_ID,$database);
				} // end of foreach $details
			} // end of if details > 0
			
			// ==============================================================================================
			// ================================= SAVING IMGAGES TO DATABASE =================================
			// ==============================================================================================

			foreach ($photos as $image_name) {
				$photo_exploded = explode(".", $image_name);
				$img_type = end($photo_exploded);

				$dir = '../images/uploads/';
				// check if file exists, if not then die 
				file_exists($dir."temp/".$image_name) or die(Translate::string("save_advertisement.temp_file_missing"));
				// change the temp file permission so that
				chmod($dir."temp/".$image_name, 0777)  or die(Translate::string("save_advertisement.temp_file_missing"));
				$image = new SimpleImage();
				$image->load($dir.'temp/'.$image_name);
				$image->resizeToWidth(900);
				$image->save($dir.'large/'.$image_name);
				$image->resizeToWidth(580);
				$image->save($dir.'medium/'.$image_name);
				$image->resizeToWidth(220);
				$image->save($dir.'small/'.$image_name);
				$image->resizeToWidth(85);
				$image->save($dir.'thumbnail/'.$image_name);

				// copy temp file to orig folder
				copy($dir.'temp/'.$image_name, $dir.'orig/'.$image_name) or die(Translate::string("save_advertisement.temp_file_missing"));
				// delete temp file
				unlink($dir.'temp/'.$image_name) or die(Translate::string("save_advertisement.temp_file_deleting"));

				// insert photo reference to database
				$database->query('INSERT INTO product_images ( product_id, img_type, date_uploaded, uuid)
													  VALUES (:product_id,:img_type,:date_uploaded,:uuid)');
				$database->bind(':product_id',$productID );
				$database->bind(':img_type',$img_type );
				$database->bind(':date_uploaded', date("Y-m-d") );
				$database->bind(':uuid',$image_name );
				if (!$database->execute()) 
					throw new Exception(Translate::string("save_advertisement.error_saving_img"), 1);
			}

			$Difference = round(microtime(true) - $TimeStart,3)*1000; // get the time this method took
			// echo $Difference." : milliseconds";

			$database->endTransaction();
			echo $productID;
		} catch (Exception $e) {
			echo 'Error2: ' .$e->getMessage();
		}

	} // end of insertToDB method

	private function insertSpecs($specsValueArray,$productID,$attribute_ID,$database)
	{
		if (!is_array($specsValueArray)) $specsValueArray = explode("RANDOM_STRING_alardas.djflkaj,bkla|ufhiwu", $specsValueArray); // convert a string to an array with one value (nessecary for the foreach loop coming up)
		$specs_array = array_filter($specsValueArray); // filter out empty array values

		foreach ($specs_array as $spec_name) {

			// make sure if the new spec is actually new or maybe it already exist in the database
			$database->query('SELECT id FROM specs WHERE LOWER(name) = :name LIMIT 1');
			$database->bind(':name', strtolower($spec_name) );
			$spec_row = $database->single();

			// if it exist
			if ($spec_row) {
				// die("die true");
				$specID = $spec_row["id"];
				// increase the count
				$database->query('UPDATE specs SET count = count+1 WHERE id = :id LIMIT 1');
				$database->bind(':id', $specID);
				if (!$database->execute()) 
					throw new Exception(Translate::string("save_advertisement.error_updating_specs"), 1);

			// if the spec is infact new and does not exist in the database
			} else {
				// if ($spec_name != "60" and $spec_name != "Sport seats") die("die false ".$spec_name);
				// prepare to insert the new spec to the database
				$database->query('INSERT INTO specs (name, slug) VALUES (:name, :slug)');
				// create a slug
				$slug = $this->slugify($spec_name);
				// bind the query and insert the new spec
				$database->bind(':name', $spec_name);
				$database->bind(':slug', $slug);
				if (!$database->execute()) 
					throw new Exception(Translate::string("save_advertisement.error_saving_specs"), 1);
				// set the new spec ID
				$specID = $database->lastInsertId();
			}

			// =============================
			// ATTRIBUTE & SPEC RELATIONSHIP
			// =============================
			$database->query('INSERT INTO product_specs (product_id, attribute_id, spec_id) VALUES (:product_id, :attribute_id, :spec_id) ');
			$database->bind(':product_id', $productID);
			$database->bind(':attribute_id', $attribute_ID);
			$database->bind(':spec_id', $specID);
			if (!$database->execute()) 
				throw new Exception(Translate::string("save_advertisement.error_saving_specs"), 1);
				
		} // end of foreach specs_array
	}

	private function handleExtras($details, $input_label, $input_value, $database, $subCategorieID, $productID)
	{

				// if the key contained checkbox then set the type to checbox, otherwise it is a text
				if (is_array($details[$input_label])) $details[$input_label] = array_filter($details[$input_label]);
				if (is_array($details[$input_value])) $details[$input_value] = array_filter($details[$input_value]);

				$count_detail_label = count($details[$input_label]);
				$count_detail_info = count($details[$input_value]);

				// if they are present and equal the same amout, then
				if ( $count_detail_label > 0 && $count_detail_info >= $count_detail_label) {
					$type = ( strpos($input_label, "checkbox") ? "checkbox" : "text" );
					for ($i=0; $i < $count_detail_label ; $i++) { 
						$new_attribute_name = $details[$input_label][$i];
						$new_attribute_value = $details[$input_value][$i];
						
						// make sure if the new attribute is actually new or maybe it already exist in the database
						$database->query('SELECT id FROM attributes WHERE LOWER(name) = :name AND sub_cat_id = :subCategorieID LIMIT 1');
						$database->bind(':name', strtolower($new_attribute_name) );
						$database->bind(':subCategorieID', $subCategorieID );
						$attribute_row = $database->single();

						// if it exist
						if ($attribute_row) {
							$attribute_ID = $attribute_row["id"];
							// increase the count
							$database->query('UPDATE attributes SET count = count+1 WHERE id = :id LIMIT 1');
							$database->bind(':id', $attribute_ID);
							$database->execute();

						// if the spec is infact new and does not exist in the database
						} else {
							// prepare to insert the new spec to the database
							$database->query('INSERT INTO attributes (sub_cat_id, name, slug, type) VALUES (:sub_cat_id, :name, :slug, :type)');
							// create a slug
							$slug = $this->slugify($new_attribute_name);
							// bind the query and insert the new spec
							$database->bind(':sub_cat_id', $subCategorieID);
							$database->bind(':name', $new_attribute_name);
							$database->bind(':slug', $slug);
							$database->bind(':type', $type);
							$database->execute();
							// set the new spec ID
							$attribute_ID = $database->lastInsertId();
						}

						$this->insertSpecs($new_attribute_value,$productID,$attribute_ID,$database);
					} // enf of for loop

					// after all the new attributes and values have been added to the database and the details array
					// remove the inital detail-label and detail-info elements from the details array
					unset($details[$input_label]);
					unset($details[$input_value]);
				} // enf of if count_label

	}

	public function slugify($text)
	{ 
	  $text = preg_replace('~[^\\pL\d]+~u', '-', $text); 	// replace non letter or digits by -
	  $text = trim($text, '-'); 							// trim
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text); 	// transliterate
	  $text = strtolower($text); 							// lowercase
	  $text = preg_replace('~[^-\w]+~', '', $text); 		// remove unwanted characters
	  if (empty($text)) return 'n-a';
	  return $text;
	}	

	public function getCompareItem($advert_id)
	{ 
		$db = new Database();
		$db->query("SELECT id, title, price, currency, city_name, country_code, zip, date_created FROM products_view WHERE id = :id LIMIT 1");
		$db->bind(":id", $advert_id );
		$advert = $db->single();

		if ($advert) {
			$db->query("SELECT uuid FROM product_images WHERE product_id = :id ORDER BY date_uploaded ASC LIMIT 1");
			$db->bind(":id", $advert_id );
			$advert_img = $db->single();
			?>
			<div class="compare-item row">
				<div class="cell"><div class="compare-thumb"><img alt="test image" src="lib/images/uploads/thumbnail/<?php echo $advert_img["uuid"]; ?>"></div></div>
				<div class="cell"><?php echo $advert["id"]; ?></div>
				<div class="cell"><?php echo $advert["title"]; ?></div>
				<div class="cell"><?php echo $advert["price"]; ?> <?php echo $advert["currency"]; ?></div>
				<div class="cell"><?php echo $advert["city_name"]; ?> (<?php echo $advert["zip"]; ?>), <?php echo strtoupper($advert["country_code"]); ?></div>
				<div class="cell"><?php echo $advert["date_created"]; ?></div>
				<div class="cell"><a data-id="<?php echo $advert["id"]; ?><" class="remove-link remove-from-compare-btn"><?php echo Translate::string("compare.remove"); ?></a></div>
			</div>
			<?
		} else {
			echo "<p>No advert found for compare</p>";
		}
	}

	public function getTopAds($categoryID = "%", $page, $limit = 8)
	{ 
		if (!isset($page)) $page = 0;
		$offset = ($limit * $page);
		$db = new Database();
		$db->query("SELECT id, title, price, currency FROM products_view WHERE top_add = 1 AND sub_category_id LIKE :categoryID ORDER BY date_created DESC LIMIT ".$limit." OFFSET ".$offset);
		$db->bind(":categoryID", $categoryID );
		$topAds = $db->fetchAll();
		if ($topAds) {
			if (count($topAds) < $limit) {
				$new_offset = 0;
				$new_limit = $limit - count($topAds);
				$db->query("SELECT id, title, price, currency FROM products_view WHERE top_add = 1 ORDER BY date_created DESC LIMIT ".$new_limit." OFFSET ".$new_offset);
				// $topAds = $db->fetchAll();
				$topAds = array_merge($topAds, $db->fetchAll() );

			}
			foreach ($topAds as $key => $advert) {
				$db->query("SELECT uuid FROM product_images WHERE product_id = :id ORDER BY date_uploaded ASC LIMIT 1");
				$db->bind(":id", $advert["id"] );
				$advert_img = $db->single();
				?><a class="top-ad-item" data-id="<?php echo $advert["id"]; ?>" href="">
					<img src="lib/images/uploads/thumbnail/<?php echo $advert_img["uuid"]; ?>" alt="<?php echo $advert["title"]; ?> thumbnail image">
					<div class="top-ad-text">
						<h4><?php echo $advert["title"]; ?></h4>
						<p><?php echo $advert["price"]; ?> <?php echo $advert["currency"]; ?></p>
					</div>
				</a><?
			}
		} else {
			self::getTopAds("%", 0, $limit);
		}
	}

	public function searchAdvertisment($sub_category_id, $search_string)
	{
		$db = new Database();
		$db->query("SELECT id, title, city_name, zip, price, currency, date_created, sub_category, top_add FROM products_view WHERE sub_category_id LIKE :sub_category_id AND (title LIKE :search_string1 OR id IN (SELECT product_id FROM product_specs_view WHERE spec_name IN (:search_string2) )) ORDER BY date_created DESC");
		$db->bind(":sub_category_id", $sub_category_id."%" );

		$search_string2 = explode(" ", $search_string);
		$search_string2 = implode(", ", $search_string2);
		$db->bind(":search_string1", "%".$search_string."%" );
		$db->bind(":search_string2", $search_string2 );
		$results = $db->fetchAll();
		return $results;
	}

	// abstract public function display(); // this means that it forces all the children classes to have a display function
}

?>
<?php 
	if (!$_SESSION) session_start();
 
	if (isset($_POST["subCatID"])) {
		$subCatID = $_POST["subCatID"];
		define('ALLOW_ACCESS', true); // allow access to this page
		spl_autoload_register(function ($class) {
			require_once "../../../classes/".$class.".class.php";
		});
		// require_once "../../../includes/sanitize-all.php";

		// load the smart-search script ?>
		<script src="lib/js/form-elements.js"></script>
		<script src="lib/js/smart-search.js"></script>
		<script src="lib/js/upload-multiple-images.js"></script>
	<?php
	}

	defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.

 ?>

<section id="step-1b-column-1" class="third left">
<h3><?php echo Translate::string("create_ad.general_info"); ?></h3>

	<?php 

		FormElement::input(array(
			'id' => "advert-title",
			'name' => "title",
			'label' => Translate::string("create_ad_form.title_label")." *",
			'class' => "one-liner",
			'required' => true,
			)); 

		FormElement::input(array(
			'id' => "advert-price",
			'name' => "price",
			'label' => Translate::string("create_ad_form.price_label")." *",
			'class' => "one-liner",
			'type' => "text",
			'required' => true,
			));
	?>

	<div class="form-element one-liner">
		<label for="advert-currency"><?php echo Translate::string("create_ad_form.currency_label"); ?> *</label>
		<select  class="btn" id="advert-currency" name="currencyID">
			<?php 
				$currencies = FormElement::getCurrencies();
				if ($currencies) {
					foreach ($currencies as $currency) {
						echo '<option value="'.$currency['id'].'">'.$currency['currency'].'</option>';
					}
				}
			 ?>
		</select>
	</div>

	<div class="form-element one-liner">
		<label for="advert-payment-method"><?php echo Translate::string("create_ad_form.payment_method_label"); ?> *</label>
		<select  class="btn" id="advert-payment-method" name="paymentMethod">
			<?php 
				$methods = FormElement::getPaymentMethods();
				if ($methods) {
					foreach ($methods as $method) {
						echo '<option value="'.$method['id'].'">'.$method['method'].'</option>';
					}
				}
			 ?>
		</select>
	</div>

	<?php

		FormElement::input(array(	
			'id' => "advert-zip",
			'name' => "zip",
			'label' => Translate::string("create_ad_form.postal_code_label")." *",
			'class' => "one-liner",
			'required' => true,
			 ));

		FormElement::input(array(	
			'id' => "advert-city",
			'name' => "city_name",
			'label' => Translate::string("create_ad_form.city_name_label")." *",
			'class' => "one-liner",
			'required' => true,
			 ));
	?>

	<div class="form-element one-liner">
		<label for="advert-country-code"><?php echo Translate::string("create_ad_form.country_label"); ?> *</label>
		<select  class="btn" id="advert-country-code" name="country_code">
			<option value="no">Nor</option>
			<option value="se">Swe</option>
			<option value="dk">Dk</option>
		</select>
	</div>

	<div class="form-element one-liner">
		<label for="advert-description"><?php echo Translate::string("create_ad_form.description_label"); ?> *</label>
		<textarea name="description" id="advert-description" required="required" rows="7" maxlength="1000" ></textarea>
	</div>

	<div class="form-element one-liner">
		<label for="advert-lang"><?php echo Translate::string("create_ad_form.language_label"); ?> *</label>
		<select  class="btn" id="advert-lang" name="languageID">
			<?php 
				$Langs = FormElement::getLanguages();
				if ($Langs) {
					foreach ($Langs as $lang) {
						echo '<option value="'.$lang['id'].'">'.$lang['eng_name'].' ('.$lang['native_name'].')</option>';
					}
				}
			 ?>
		</select>
	</div>
<!-- 
	<div class="form-element one-liner">
		<label for="advert-img-input">Upload photos *</label>
		<input id="advert-img-input" type="file" multiple name="photos[]" class="btn">
	</div>
 -->
</section>

<section id="step-1b-column-2" class="third left">
<h3><?php echo Translate::string("create_ad.specific_info"); ?></h3>

	<?php Product::getAttributes($subCatID, "exclude-checkboxes", 12); ?>
<!-- 
	<div class="form-element">
		<label for="finnplus-dropdown">Dropdown</label>
		<div class="dropdown">
			<label for="dropdown-2" class="dropdown-btn btn">Dropdown</label><input id="dropdown-2" type="checkbox" class="dropdown-checkbox" >
			<ul>
				<li>
					<label for="option-a">option A</label><input type="radio" id="option-a" value="a" name="finnplus-dropdown">
				</li>
				<li>
					<label for="option-b">option B</label>
					<input type="radio" id="option-b" value="b" name="finnplus-dropdown">
				</li>
				<li>
					<label for="option-c">option C</label>
					<input type="radio" id="option-c" value="c" name="finnplus-dropdown">
				</li>
			</ul>
		</div>
	</div> -->

</section>

<section id="step-1b-column-3" class="third left">
	<h3><?php echo Translate::string("create_ad.extra_details"); ?></h3>

	<?php Product::getAttributes($subCatID, "only-checkboxes", 5); ?>


</section>

<button class="btn fullwidth" style="margin:60px 0 10px 0;"><?php echo Translate::string("create_ad.continue_to_next_step"); ?></button>
<label id="back-to-step1a" for="step-1a-checkbox" class="btn fullwidth" ><?php echo Translate::string("create_ad.back_to_prev_step"); ?></label>



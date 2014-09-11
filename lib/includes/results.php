<?php 
	if (!$_SESSION) session_start();
	// define('ALLOW_ACCESS', true); // allow access to this page
	defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.
 ?>

	<section id="results" role="main">
		<div class="container">
			<form id="results-control" method="POST" <?php echo (isset($_GET["my-ads"])) ? "style='display:none;'" : "" ; ?> role="complementary">

				<?php 

					// View Mode
					if ( isset($_POST["view-mode"]) && !empty($_POST["view-mode"]) ) {
						$view_mode = $_POST["view-mode"];
					} else {
						$view_mode = "gallery";
					}

					// Show
					if ( isset($_POST["show"]) && !empty($_POST["show"]) ) {
						$show = $_POST["show"];
					} else {
						$show = 20;
					}

				 ?>


				<input type="radio" name="view-mode" class="view-mode-input hidden" id="gallery-view-radio" value="gallery" <?php echo ($view_mode == "gallery") ? 'checked="checked"' : "" ; ?> >
				<label for="gallery-view-radio" id="gallery-view-btn" class="btn btn-img right">
					<img src="lib/images/elements/gallery-view-img.svg" alt="Gallery View">
				</label>

				<input type="radio" name="view-mode" class="view-mode-input hidden" id="list-view-radio" value="list" <?php echo ($view_mode == "list") ? 'checked="checked"' : "" ; ?>>
				<label for="list-view-radio" id="list-view-btn" class="btn btn-img right">
					<img src="lib/images/elements/list-view-img.svg" alt="List View">
				</label>

				<select name="show" id="show-select" class="btn right">
					<option value="10" <?php echo ($show == "10") ? "selected='selected'" : "" ; ?> ><?php echo Translate::string("results_controller.show_select",$lang); ?> 10</option>
					<option value="20" <?php echo ($show == "20") ? "selected='selected'" : "" ; ?> ><?php echo Translate::string("results_controller.show_select",$lang); ?> 20</option>
					<option value="40" <?php echo ($show == "40") ? "selected='selected'" : "" ; ?> ><?php echo Translate::string("results_controller.show_select",$lang); ?> 40</option>
					<option value="100" <?php echo ($show == "100") ? "selected='selected'" : "" ; ?> ><?php echo Translate::string("results_controller.show_select",$lang); ?> 100</option>
				</select>

				<select id="sort-by-select" name="sort" class="btn right">
					<option value=""><?php echo Translate::string("results_controller.sort_by",$lang); ?></option>
					<option value="price ASC"><?php echo Translate::string("results_controller.price",$lang); ?> &#9650;</option>
					<option value="price DESC"><?php echo Translate::string("results_controller.price",$lang); ?> &#9660;</option>
					<option value="date_created ASC"><?php echo Translate::string("results_controller.date",$lang); ?> &#9650;</option>
					<option value="date_created DESC"><?php echo Translate::string("results_controller.date",$lang); ?> &#9660;</option>
				</select>
				<?php // <label id="sort-by-label" for="sort-by-select" class="right">Sort by: </label> ?>

				<label for="compare-checkbox" id="compare-btn" class="btn left <?php echo (isset($_GET["compare"])) ? 'btn-active' : '' ; ?>"><?php echo Translate::string("results_controller.compare_btn",$lang); ?></label>
				<input type="checkbox" id="result-refine-checkbox" class="hidden" checked>
				<label for="result-refine-checkbox" class="btn left"><?php echo Translate::string("results_controller.refine_search_btn",$lang); ?></label>

				<noscript>
					<input type="submit" value="Apply!" class="btn left">
				</noscript>

				<div id="result-refine-container" class="left">
					<select name="price" class="btn select-arrow">
						<option value="">Car Type:</option>
						<option value="0-99">Sedan</option>
						<option value="100-199">Coupee</option>
						<option value="100-199">Race-Car</option>
						<option value="100-199">Truck</option>
						<option value="100-199">Jeep</option>
					</select>

					<select name="price" class="btn select-arrow">
						<option value="">Price:</option>
						<option value="0-99">0€ - 99€</option>
						<option value="100-199">100€ - 199€</option>
					</select>

					<select name="area" class="btn select-arrow">
						<option value="">Area:</option>
						<option value="0-99">0€ - 99€</option>
						<option value="100-199">100€ - 199€</option>
					</select>

					<select name="location" class="btn select-arrow">
						<option value="">Location:</option>
						<option value="0-99">0€ - 99€</option>
						<option value="100-199">100€ - 199€</option>
					</select>

					<select name="renovation" class="btn select-arrow">
						<option value="">Renovation:</option>
						<option value="">Newly renovated</option>
						<option value="">Renovation needed</option>
					</select>
				</div>

				<input id="page-input" name='page' value='1' type='hidden'>
				<input id="sub_cat_id-input" name='sub_cat_id' value='<?php echo (int) $_GET["sub_cat_id"]; ?>' type='hidden'>
			</form>

			<div id="results-list" >
				<?php if (isset($_GET["my-ads"])) {
					require_once("lib/includes/my-ads.php");
				}  else {
					require_once("lib/includes/results-list.php");
				} ?>
			</div>
				
		</div>
	</section><?php // / #results ?>
<?php 
	if (!$_SESSION) session_start();

	// define('ALLOW_ACCESS', true); // allow access to this page
	// defined('ALLOW_ACCESS') or die('Restricted access'); 	// Security to prevent direct access to php files.

	// View Mode
	if ( isset($_POST["view-mode"]) && !empty($_POST["view-mode"]) ) {
		$view_mode = $_POST["view-mode"];
	} elseif ( isset($_GET["view-mode"]) && !empty($_GET["view-mode"]) ) {
		$view_mode = $_GET["view-mode"];
	} else {
		$view_mode = "gallery";
	}

	if ( isset($_POST["sort"]) && !empty($_POST["sort"]) ) {
		$sort = $_POST["sort"];
	} else {
		$sort = "id DESC";
	}

	if ( isset($_POST["page"]) && !empty($_POST["page"]) ) {
		$page = (int) $_POST["page"];
		$page--;
	} else {
		$page = 0;
	}


	if ( isset($_GET["sub_cat_id"]) && !empty($_GET["sub_cat_id"]) ) {
		$subCatID = (int) $_GET["sub_cat_id"];
	} else if ( isset($_POST["sub_cat_id"]) && !empty($_POST["sub_cat_id"]) ) {
		$subCatID = (int) $_POST["sub_cat_id"];
	} else {
		$subCatID = "%";
	}


	// Show
	if ( isset($_POST["show"]) && !empty($_POST["show"]) ) {
		$show = $_POST["show"];
	} else {
		$show = 20;
	}

	$offset = $show * $page;

	// die("die");

	$error = null;
	try {
		spl_autoload_register(function ($class) { require_once "../classes/".$class.".class.php"; });
		
		$db = new Database;
		$db->query('SELECT name FROM categories_sub WHERE id = :subCatID LIMIT 1');
		$db->bind(':subCatID', $subCatID);
		$category = $db->single();
		
		$db->query('SELECT count(id) as total FROM products_view WHERE status = "published" AND sub_category_id LIKE :subCatID  AND status = "published"');
		$db->bind(':subCatID', $subCatID);
		$count = $db->single();

		$statement = 'SELECT id, title, city_name, zip, price, currency, date_created, sub_category, top_add FROM products_view WHERE sub_category_id LIKE :subCatID AND status = "published" ORDER BY '.$sort.' LIMIT '.$show.' OFFSET '.$offset;
		$db->query($statement);
		$db->bind(':subCatID', $subCatID);
		$products = $db->fetchAll();

	} catch (Exception $e) {
		$error = $e->getMessage();
	}

?>
<?php if ($error): ?>
	<h2><?php echo $error ?></h2>
<?php endif ?>
<h2 class="results-list-title"><?php echo Translate::string("results.found"); ?> <?php echo $count["total"]; ?> <?php echo ($category) ? $category["name"] : Translate::string("results.latest_advertisements") ; ?></h2>
<?php if ($products): ?>
	<div id="results-container" class="<?php echo ($view_mode == "gallery") ? "gallery-view" : "list-view" ; ?>">
	<?php require_once("advert-item.php"); ?>
	</div>

	<?php if ($count["total"] > $show): ?>
		<?php $total = ceil($count["total"] / $show); ?>
		<section id="pager">
			<a href="" class="btn prev-page">&#9664; <?php echo Translate::string("results.prev_page"); ?></a>
			<select name="page" class="btn select-arrow" >
				<?php for ($i=0; $i < $total; $i++) { ?>
					<option value="<?php echo $i+1; ?>" <?php echo ($page == $i) ? "selected" : ""; ?>><?php echo Translate::string("results.page"); ?> <?php echo $i+1; ?> of <?php echo $total; ?></option>
				<?php } ?>
			</select>
			<input type="hidden" id="total-pages" value="<?php echo $total; ?>">
			<a href="" class="btn next-page"><?php echo Translate::string("results.next_page"); ?> &#9654;</a>
		</section>
	<?php endif ?>
	<script src="lib/js/results-list.js"></script>
<?php endif ?>
<?php 
	if (!$_SESSION) session_start();

	// define('ALLOW_ACCESS', true); // allow access to this page
	// defined('ALLOW_ACCESS') or die('Restricted access'); 	// Security to prevent direct access to php files.

	// View Mode
	if ( isset($_POST["view-mode"]) && !empty($_POST["view-mode"]) ) {
		$view_mode = $_POST["view-mode"];
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


	// Show
	if ( isset($_POST["show"]) && !empty($_POST["show"]) ) {
		$show = $_POST["show"];
	} else {
		$show = 20;
	}

	$offset = $show * $page;


	if ( isset($_POST["my_ads"]) ) {
		require_once "session.php";
	}

	// die("die");

	$userID = $_SESSION["user_id"];

	$error = null;
	try {
		spl_autoload_register(function ($class) { require_once "../classes/".$class.".class.php"; });
		$db = new Database;
		$statement = 'SELECT id, title, city_name, zip, price, currency, date_created, sub_category, status, top_add FROM products_view WHERE user_id = :userID ORDER BY date_created DESC '; // ORDER BY '.$sort.' LIMIT '.$show.' OFFSET '.$offset;
		$db->query($statement);
		$db->bind(':userID', $userID);
		$products = $db->fetchAll();

		$count = count($products);

	} catch (Exception $e) {
		$error = $e->getMessage();
	}

?>
<?php if ($error): ?>
	<h2><?php echo $error ?></h2>
<?php endif ?>
<h2 class="results-list-title"><?php echo sprintf(Translate::string("my_ads.results_title"), $count); ?></h2>
<?php if ($products): ?>
	<div class="<?php echo ($view_mode == "gallery") ? "gallery-view" : "list-view" ; ?>">
		<?php require_once("advert-item.php"); ?>
	</div>

	<?php if ($count > $show AND $count > 999999999): ?>
		<?php $total = ceil($count / $show); ?>
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
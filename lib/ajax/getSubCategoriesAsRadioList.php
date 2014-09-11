<?php 
	if (!$_SESSION) session_start();

	if (isset($_POST["cat_id"])) {
		$mainCatID = $_POST["cat_id"];
		spl_autoload_register(function ($class)
		{
			require_once "../classes/".$class.".class.php";
		});
		require_once "../includes/sanitize-all.php";
		define('ALLOW_ACCESS', true); // allow access to this page when it is loaded with ajax

	} else {
		if ($admin) {
			require_once "../lib/includes/sanitize-all.php";
		} else {
			require_once "lib/includes/sanitize-all.php";
		}
	}

	defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.

	$db = new Database();
	$subCategories = $db->getSubCategoriesArray($mainCatID, "CASE WHEN name = 'Other' THEN 2 ELSE 1 END,name ASC"); 

	$subCatID = 0;
	if (!empty($_GET["sub_cat_id"])) {
		$subCatID = $_GET["sub_cat_id"];
	}

?>


<?php if ($subCategories): ?>
	<?php foreach ($subCategories as $cat): ?>
	<?php $status = ($cat["id"] == $subCatID) ? "checked='checked'" : ""; ?>
	<li>
		<input <?php echo $status; ?> required type="radio" name="subCategory" value="<?php echo $cat["id"]; ?>" id="sub-cat-<?php echo $cat["id"]; ?>">
		<label for="sub-cat-<?php echo $cat["id"]; ?>"><a class="preventDefault" href="?cat_id=<?php echo $mainCatID; ?>&sub_cat_id=<?php echo $cat["id"]; ?>#advert-create"><?php echo Translate::string("categorySub.".Product::slugify($cat["name"])); ?></a></label>
	</li>
	<?php endforeach ?>
<?php else: ?>
	<li>
		<input type="radio" name="subCategory" value="" id="sub-cat-0">
		<label for="sub-cat-0"><?php echo Translate::string("categories.no_sub_categories"); ?></label>
	</li>
<?php endif ?>

<script type="text/javascript">
	$("#create-sub-cat li").unbind().click(function (e) {
		e.preventDefault();
		// uncheck whatever was checked before by the php $_GET
		$("#create-sub-cat input").prop("checked", false);
		// make the currently clicked input to checked
		$(this).children("input").prop("checked", true);
	});
</script>
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

	require_once "../lib/includes/sanitize-all.php";

	$subCatID = 0;
	if (!empty($_GET["sub_cat_id"])) {
		$subCatID = $_GET["sub_cat_id"];
	}

?>


<?php if ($subCategories): ?>
	<?php foreach ($subCategories as $cat): ?>
			<tr>
				<form class="change-subcategory-form" action="ajax/change-subcategory.php" method="POST">
					<td><input class="form-control" type="text" name="name" value="<?php echo $cat["name"]; ?>" required><input type="hidden" name="id" value="<?php echo $cat["id"]; ?>" required ></td>
					<td><input class="form-control" type="text" name="10_day_price_nok" value="<?php echo $cat["10_day_price_nok"]; ?>" required></td>
					<td><input class="form-control" type="text" name="20_day_price_nok" value="<?php echo $cat["20_day_price_nok"]; ?>" required></td>
					<td><input class="form-control" type="text" name="30_day_price_nok" value="<?php echo $cat["30_day_price_nok"]; ?>" required></td>
					<td><input class="form-control" type="text" name="top_add_price_nok" value="<?php echo $cat["top_add_price_nok"]; ?>" required></td>
					<td><input class="form-control" type="text" name="video_price_nok" value="<?php echo $cat["video_price_nok"]; ?>" required>		</td>
					<td><input class="form-control" type="text" name="bold_view_price_nok" value="<?php echo $cat["bold_view_price_nok"]; ?>" required></td>
					<td><input class="form-control" type="text" name="top_search_price_nok" value="<?php echo $cat["top_search_price_nok"]; ?>" required></td>
					<td><button type="submit" class="btn btn-warning">Save</button></td>
					<td><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button></td>
				</form>
			</tr>
	<?php endforeach ?>
<?php else: ?>
	<tr>
		<td colspan="9">No Sub Categories</td>
	</tr>
<?php endif ?>

<script type="text/javascript">
	
</script>
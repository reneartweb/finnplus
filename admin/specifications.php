<?php 

define('ALLOW_ACCESS', true); // allow access to this page
require_once("includes/header.php"); 
$db = new Database;

// $mainCatID = false;

if (isset($_GET["cat_id"])) {
	$mainCatID = $_GET["cat_id"];
	$subCategories = $db->getSubCategoriesArray($mainCatID, "CASE WHEN name = 'Other' THEN 2 ELSE 1 END,name ASC");
	require_once "../lib/includes/sanitize-all.php";
}
$mainCategories = $db->getMainCategoriesArray("case when name = 'Deleted' then 3 when name = 'Free Stuff' then 2 else 1 end,name desc");

$subCatID = NULL;
if (isset($_GET["sub_cat_id"])) {
	$subCatID = $_GET["sub_cat_id"];
	$db->query('SELECT id, slug, name, name_nor, type, count FROM attributes WHERE sub_cat_id = :subCatID ORDER BY name ASC');
	$db->bind(':subCatID', $subCatID);
	$attributes = $db->fetchAll();
}

if (isset($_GET["attr_id"])) {
	$attrID = $_GET["attr_id"];
	$db->query('SELECT id, name, name_nor, slug, count FROM specs WHERE id IN ( SELECT DISTINCT spec_id FROM product_specs WHERE attribute_id = :attrID ) ORDER BY name DESC ');
	// $db->query('SELECT id, name, slug, count FROM specs WHERE id IN ( SELECT DISTINCT spec_id FROM product_specs WHERE attribute_id = :attrID AND product_id IN (SELECT id FROM products WHERE sub_cat_id = :subCatID ) ) ORDER BY name DESC ');
	// $db->bind(':subCatID', $subCatID);
	$db->bind(':attrID', $attrID);
	$specifications = $db->fetchAll();
}

$change_attribute = false;
if (isset($_GET["change_attribute"])) {
	$change_attribute = $change_attribute;
}





?>

<div class="row">
<section id="create-main-cat" class="col-md-2">
	<h4>Main Categories</h4>
	<ul class="list-unstyled">
	<?php foreach ($mainCategories as $cat): ?> 
		<li><a class="btn btn-sm btn-block btn-default <?php echo ($cat["id"] == $mainCatID) ? "active" : ""; ?>" title="<?php echo $cat["name"]; ?>" href="?cat_id=<?php echo $cat["id"]; ?>"><?php echo $cat["name"]; ?></a></li>
	<?php endforeach ?>
	</ul>
</section>

<?php if ($subCategories): ?>
	<section class="col-md-2">
		<h4>Sub-Categories</h4>
		<ul class="list-unstyled">
			<?php foreach ($subCategories as $cat): ?> 
				<li><a style="overflow:hidden;" title="<?php echo $cat["name"]; ?>" class="btn btn-sm btn-block btn-default <?php echo ($cat["id"] == $subCatID) ? "active" : ""; ?>" href="?cat_id=<?php echo $mainCatID; ?>&sub_cat_id=<?php echo $cat["id"]; ?>"><?php echo $cat["name"]; ?></a></li>
			<?php endforeach ?>
		</ul>
</section>
<?php endif ?>

<?php if ($attributes): ?>
	<section class="col-md-2">
		<h4>Attributes</h4>
		<ul class="list-unstyled">
			<?php foreach ($attributes as $attr): ?> 
				<li>
					<div class="btn-group btn-block btn-group-sm">
					  <a style="overflow:hidden;" href="?cat_id=<?php echo $mainCatID; ?>&sub_cat_id=<?php echo $subCatID; ?>&attr_id=<?php echo $attr["id"]; ?>" title="<?php echo $attr["name"]; ?>" class="btn btn-default <?php echo ($attr["id"] == $attrID) ? "active" : ""; ?>"><?php echo $attr["name"]; ?></a>
					  <!-- <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"> -->
					    <!-- <span class="caret"></span> -->
					    <!-- <span class="sr-only">Toggle Dropdown</span> -->
					  <!-- </button> -->
					  <!-- <ul class="dropdown-menu" role="menu"> -->
					  	<!-- <li><a href="#" data-id="<?php echo $attr["id"]; ?>" class="change-attribute-btn"> <span class="glyphicon glyphicon-pencil"></span> Edit</a></li> -->
					    <!-- <li><a class="replace-spec" data-name="<?php // echo $cat["name"]; ?>" data-id="<?php // echo $cat["id"]; ?>" href="#" ><span class="glyphicon glyphicon-resize-small"></span> Merge with ...</a></li> -->
					    <!-- <li><a class="delete-spec" data-name="<?php // echo $cat["name"]; ?>" data-id="<?php // echo $cat["id"]; ?>" href="#" ><span class="glyphicon glyphicon-trash"></span> Delete</a></li> -->
					    <!-- <li class="divider"></li> -->
					  <!-- </ul> -->
					</div>
				</li>
			<?php endforeach ?>
		</ul>
	</section>
<?php endif ?>


<?php if ($specifications): ?>
	<section class="col-md-6">
		<h4>Specifications</h4>
			<div class="table-responsive well" style="padding-top: 0;">
			  <table class="table table-condensed">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name ENG</th>
						<th>Name NOR</th>
						<!-- <th>Slug</th> -->
						<th style="width: 54px;">Count</th>
						<th style="width: 98px;"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($specifications as $spec): ?> 
						<form class="change-specification-form" action="ajax/change-specification.php" method="post">
							<tr>
								<td><?php echo $spec["id"] ?> <input type="hidden" required name="id" value="<?php echo $spec["id"] ?>"></td>
								<td><span class="hidden"><?php echo $spec["name"] ?></span><input type="text" required class="form-control" name="name" value="<?php echo $spec["name"] ?>"> </td>
								<td><span class="hidden"><?php echo $spec["name_nor"] ?></span><input type="text" required class="form-control" name="name_nor" value="<?php echo $spec["name_nor"] ?>"> </td>
								<!-- <td><input type="text" required class="form-control" name="slug" value="<?php echo $spec["slug"] ?>"> </td> -->
								<td><span class="hidden"><?php echo $spec["count"] ?></span><input type="text" required class="form-control" name="count" value="<?php echo $spec["count"] ?>"> </td>
								<td>
									<div class="btn-group btn-block btn-group-sm">
									  <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
									  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
									    <span class="caret"></span>
									    <span class="sr-only">Toggle Dropdown</span>
									  </button>
									  <ul class="dropdown-menu" role="menu">
									    <li><a class="merge-spec" data-id="<?php echo $spec["id"]; ?>" href="#" ><span class="glyphicon glyphicon-resize-small"></span> Merge with ...</a></li>
									    <li><a class="delete-spec" data-id="<?php echo $spec["id"]; ?>" data-count="<?php echo $spec["count"]; ?>" href="#" ><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
									    <!-- <li class="divider"></li> -->
									  </ul>
									</div>
								</td>
							</tr>
						</form>
					<?php endforeach ?>
				</tbody>
			  </table>
			</div>
	</section>
<?php endif ?>

</div>
<?php require_once("includes/footer.php"); ?>

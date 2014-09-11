<?php 

define('ALLOW_ACCESS', true); // allow access to this page
require_once("includes/header.php"); 
$db = new Database;

$mainCatID = 1;

if (isset($_GET["cat_id"])) {
	$mainCatID = $_GET["cat_id"];
	require_once "../lib/includes/sanitize-all.php";
}

$mainCategories = $db->getMainCategoriesArray("case when name = 'Deleted' then 3 when name = 'Free Stuff' then 2 else 1 end,name desc");

?>

<div class="row">
<section id="create-main-cat" class="col-md-2 radio-switch">
	<h4>Main Categories</h4>
	<ul class="list-unstyled">
	<?php foreach ($mainCategories as $cat): ?> 
		<li><a class="btn btn-sm btn-block btn-default <?php echo ($cat["id"] == $mainCatID) ? "active" : ""; ?>" href="?cat_id=<?php echo $cat["id"]; ?>"><?php echo $cat["name"]; ?></a></li>
	<?php endforeach ?>
	</ul>
</section>

<section class="col-md-10 radio-switch">
	<h4>Sub-categories</h4>
	<div class="table-responsive well" style="padding-top: 0;">
	  <input type="checkbox" id="show-add-sub-category-form-checkbox" class="hidden" > <!-- important for add new subcategory button and form -->
	  <table class="table table-condensed">
		<thead>
			<tr>
				<th style="width: 30%;">Name</th>
				<th>10 Day</th>
				<th>20 Day</th>
				<th>30 Day</th>
				<th>Video</th>
				<th>Bold</th>
				<th>Top Ad</th>
				<th>Top Search</th>
				<th style="width: 98px;"></th>
			</tr>
		</thead>
		<tbody>

			<?php $subCategories = $db->getSubCategoriesArray($mainCatID, "CASE WHEN name = 'Other' THEN 2 ELSE 1 END,name ASC"); ?>
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
								<?php if ($mainCatID == 0): ?>
								<td>
									<select class="form-control" name="main_cat_id">
										<option value="0">----</option>
										<?php foreach ($mainCategories as $mCat): ?>
											<?php if ($mCat["id"] == 0 ) continue; ?>
											<option value="<?php echo $mCat["id"]; ?>"><?php echo $mCat["name"]; ?></option>
										<?php endforeach ?>
									</select>									
								</td>
								<?php endif ?>
								<td>
									<div class="btn-group btn-group-sm">
									  <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
									  <?php if ($mainCatID != 0): ?>
										  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
										    <span class="caret"></span>
										    <span class="sr-only">Toggle Dropdown</span>
										  </button>
										  <ul class="dropdown-menu" role="menu">
										    <li><a class="delete-sub-cat" data-name="<?php echo $cat["name"]; ?>" data-id="<?php echo $cat["id"]; ?>" href="#" ><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
												
										    <!-- <li class="divider"></li> -->
										  </ul>
										  <input type="hidden" name="main_cat_id" value="<?php echo $cat["main_cat_id"]; ?>">
									  <?php endif ?>
									</div>
								</td>
							</form>
						</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr>
					<td colspan="9">No Sub Categories</td>
				</tr>
			<?php endif ?>

			<?php if ($mainCatID != 0): ?>
				<?php if (count($subCategories) < 15): ?>
				<tr class="labelrow">
					<td colspan="9"><label class="btn btn-success" for="show-add-sub-category-form-checkbox">Add New Subcategory</label></td>
				</tr>
				<tr class="success hidden">
					<form class="add-sub-category-form" action="ajax/add-subcategory.php" method="POST">
						<td><input class="form-control" type="text" name="name" placeholder="Add New" value="" required><input type="hidden" name="main_cat_id" value="<?php echo $mainCatID; ?>" required></td>
						<td><input class="form-control" type="text" name="10_day_price_nok" value="" required></td>
						<td><input class="form-control" type="text" name="20_day_price_nok" value="" required></td>
						<td><input class="form-control" type="text" name="30_day_price_nok" value="" required></td>
						<td><input class="form-control" type="text" name="video_price_nok" value="" required>		</td>
						<td><input class="form-control" type="text" name="bold_view_price_nok" value="" required></td>
						<td><input class="form-control" type="text" name="top_add_price_nok" value="" required></td>
						<td><input class="form-control" type="text" name="top_search_price_nok" value="" required></td>
						<td><button type="submit" class="btn btn-success">Add</button></td>
					</form>
				</tr>
				<?php else: ?>
				<tr>
					<td colspan="9">Max 15 subcategories allowed</td>
				</tr>
				<?php endif ?>
			<?php endif ?>


		</tbody>
	  </table>
	</div>

</section>
</div>
<?php require_once("includes/footer.php"); ?>

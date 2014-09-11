<?php 

define('ALLOW_ACCESS', true); // allow access to this page
require_once("includes/header.php"); 

$db = new Database;

if (isset($_GET["subCat"])) {
	$db->query("SELECT name FROM categories_sub WHERE id = :subCat LIMIT 1");
	$db->bind(':subCat', $_GET["subCat"]);
	$subCat = $db->single();	
	$title = $subCat["name"];

	$db->query("SELECT id as ID, user_id as Owner, title as Title, price as Price, currency as Cur, city_name as City, UPPER(country_code) as Country, top_add as 'Top Ad', date_created as 'Created', date_last_edit as 'Last Edited', date_published as 'Published', status as Status FROM products_view WHERE sub_category_id = :subCat ORDER BY id DESC");
	$db->bind(':subCat', $_GET["subCat"]);
	$ads = $db->fetchAll();
} elseif (isset($_GET["owner"])) {
	$db->query("SELECT name FROM users WHERE id = :owner LIMIT 1");
	$db->bind(':owner', $_GET["owner"]);
	$owner = $db->single();	
	$title = $owner["name"]."'s Advertisments";

	$db->query("SELECT id as ID, user_id as Owner, title as Title, price as Price, currency as Cur, city_name as City, UPPER(country_code) as Country, top_add as 'Top Ad', date_created as 'Created', date_last_edit as 'Last Edited', date_published as 'Published', status as Status FROM products_view WHERE user_id = :owner ORDER BY id DESC");
	$db->bind(':owner', $_GET["owner"]);
	$ads = $db->fetchAll();
} else {
	$db->query("SELECT id, name FROM categories_sub WHERE id IN (SELECT DISTINCT sub_cat_id as id FROM products) ORDER BY name ASC");
	$subCats = $db->fetchAll();	
}


?>

<?php if (isset($_GET["owner"]) or isset($_GET["subCat"])): ?>
	<h1 class="page-header"><?php echo $title; ?> <small><span class="label label-default"><?php echo count($ads) ?></span></small></h1>
	
	<?php if ($ads): ?>		
		<div class="table-responsive">
			<table class="table table-hover table-condensed">
				<thead>
					<tr>
						<?php foreach ($ads[0] as $key => $value): ?>
						<th><?php echo $key ?></th>
						<?php endforeach ?>
						<th style="min-width:81px;"></th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=0; $i < count($ads); $i++) { ?>
						<tr>
							<?php foreach ($ads[$i] as $key => $value): ?>
								<?php if ($key == "Owner"): ?>
									<td><a href="users.php?id=<?php echo $value ?>">View Owner</a></td>
								<?php else: ?>
									<td><?php echo $value ?></td>
								<?php endif ?>
							<?php endforeach ?>
							<td>
								<div class="btn-group btn-group-xs">
									<button type="button" data-user-id="<?php echo $user["id"] ?>" class="btn edit-user btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</button>
									<button type="button" data-user-id="<?php echo $user["id"] ?>" class="btn delete-user btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
								</div>
							</td>
						</tr>
					<? } ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p class="well well-lg">No Advertisments found</p>
	<?php endif ?>

<?php else: ?>
	<h1 class="page-header">Advertisments</h1>
	<form action="" method="get" class="form-horizontal" role="form">
		  <div class="form-group">
		    <label for="inputEmail3" class="col-sm-2 control-label">Select Category</label>
		    <div class="col-sm-6">
				<select name="subCat" id="" class="form-control">
					<?php foreach ($subCats as $cat): ?>
						<option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
					<?php endforeach ?>
				</select>
		    </div>
		  </div>

		  <div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <button type="submit" class="btn btn-default">Continue</button>
		    </div>
		  </div>
	</form>	
<?php endif ?>

<?php require_once("includes/footer.php"); ?>

<?php 

define('ALLOW_ACCESS', true); // allow access to this page
require_once("includes/header.php"); 
$db = new Database;

$db->query('SELECT id, role FROM user_roles ORDER BY id DESC');
$roles = $db->fetchAll();

$db->query('SELECT id, eng_name FROM languages ORDER BY id DESC');
$languages = $db->fetchAll();

if (isset($_GET["id"])) {
	$db->query('SELECT users.id, users.name, users.role_id, users.email, users.phone, users.date_registered, users.birthday, users.role_id, users.lang_id, users.can_login,
					   companies.company_name, companies.company_number, companies.company_address, companies.company_zip, companies.phone_2
				FROM users, companies 
				WHERE users.id LIKE :id 
					AND users.name LIKE :name 
					AND users.role_id LIKE :role_id 
					AND users.email LIKE :email 
					AND users.phone LIKE :phone 
					AND users.date_registered LIKE :date_registered 
					AND users.birthday LIKE :birthday 
					AND users.role_id LIKE :role_id 
					AND companies.company_name LIKE :company_name 
					AND companies.company_number LIKE :company_number 
					AND companies.company_address LIKE :company_address 
					AND companies.company_zip LIKE :company_zip 
					AND companies.phone_2 LIKE :phone_2 
				ORDER BY id DESC');

	$db->bind(':id', "%".$_GET["id"]."%");
	$db->bind(':name', "%".$_GET["name"]."%");
	$db->bind(':role_id', "%".$_GET["role_id"]."%");
	$db->bind(':email', "%".$_GET["email"]."%");
	$db->bind(':phone', "%".$_GET["phone"]."%");
	$db->bind(':date_registered', "%".$_GET["date_registered"]."%");
	$db->bind(':birthday', "%".$_GET["birthday"]."%");
	$db->bind(':role_id', "%".$_GET["role_id"]."%");

	$db->bind(':company_name', "%".$_GET["company_name"]."%");
	$db->bind(':company_number', "%".$_GET["company_number"]."%");
	$db->bind(':company_address', "%".$_GET["company_address"]."%");
	$db->bind(':company_zip', "%".$_GET["company_zip"]."%");
	$db->bind(':phone_2', "%".$_GET["phone_2"]."%");

	$users = $db->fetchAll();
} else {
	$db->query('SELECT users.id, users.name, users.role_id, users.email, users.phone, users.date_registered, users.birthday, users.role_id, users.lang_id, users.can_login,
					   companies.company_name, companies.company_number, companies.company_address, companies.company_zip, companies.phone_2
				FROM users, companies WHERE companies.user_id = users.id AND users.role_id = 2 ORDER BY id DESC');
	$users = $db->fetchAll();
}


?>

<h1 class="page-header">Users <small><span class="label label-default"><?php echo count($users) ?></span></small></h1>

<?php if ($alert): ?>
	<?php echo $alert; ?>
<?php endif ?>

<form class="table-responsive user-search-form" action="" method="get">
	<table class="table table-condensed well">
		<tbody>
			<tr>
				<td style="width:5%;"><input type="text" class="form-control" placeholder="id" name="id"></td>
				<td><input type="text" class="form-control" placeholder="name" name="name"></td>
				<td><input type="text" class="form-control" placeholder="email" name="email"></td>
				<td><input type="text" class="form-control" placeholder="phone" name="phone"></td>
				<td><input type="text" class="form-control" placeholder="date_registered" name="date_registered"></td>
				<td><input type="text" class="form-control" placeholder="birthday" name="birthday"></td>
				<td style="min-width:139px;">
					<div class="btn-group">
						<button title="Search" type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
						<button title="Reset" type="reset" class="btn btn-default"><span class="glyphicon glyphicon-refresh"></span></button>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<div id="container">
	<div class="table-responsive">
		<table class="table table-hover table-condensed">
			<thead>
				<tr>
					<th>ID</th>
					<th>COMPANY_NAME</th>
					<th>NAME</th>
					<th>AD's</th>
					<th>CVR</th>
					<th>ADDRESS</th>
					<th>ZIP</th>
					<th>EMAIL</th>
					<th>PHONE 1</th>
					<th>PHONE 2</th>
					<th>REG_DATE</th>
					<th>BIRTHDAY</th>
					<th>LANG</th>
					<th>CAN_LOGIN</th>
					<th></th>
					<th style="min-width:101px;"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user): ?>
					<tr>
						<?php 
								$db->query('SELECT count(id) as count FROM products WHERE user_id = :userID ');
								$db->bind(':userID', $user["id"]);
								$p = $db->single();
	 					?>
						<td><?php echo $user["id"] ?></td>
						<?php if ($p["count"] > 0): ?>
							<td><a title="View User Ad's" href="advertisments.php?owner=<?php echo $user["id"] ?>"><?php echo $user["company_name"] ?></a></td>
						<?php else: ?>
							<td title="No Ad's"><?php echo $user["company_name"] ?></td>
						<?php endif ?>
						<td><?php echo $user["name"] ?></td>
						<td>
							<?php if ($p["count"] > 0): ?>
								<a title="View User Ad's" href="advertisments.php?owner=<?php echo $user["id"] ?>">
									<span class="badge"><?php echo $p["count"];?></span>
								</a>
							<?php else: ?>
									<span class="badge">Zero</span>						
							<?php endif ?>
						</td>
						<td><?php echo $user["company_number"] ?></td>
						<td><?php echo $user["company_address"] ?></td>
						<td><?php echo $user["company_zip"] ?></td>
						<td><?php echo $user["email"] ?></td>
						<td><?php echo $user["phone"] ?></td>
						<td><?php echo $user["phone_2"] ?></td>
						<td><?php echo $user["date_registered"] ?></td>
						<td><?php echo $user["birthday"] ?></td>
						<td>
							<?php foreach ($languages as $l): ?>
								<?php echo ($l["id"] == $user["lang_id"]) ? $l["eng_name"] : "" ; ?>
							<?php endforeach ?>
						</td>
						<td>
							<form class="change-login-permission-form" action="ajax/change-login-permission.php" method="post">
								<input type="hidden" name="user_id" value="<?php echo $user["id"]; ?>">
								<input value="1" type="checkbox" name="can_login" class="bootstrapSwitch"  <?php echo ($user["can_login"]) ? "checked" : "" ; ?> <?php echo ($user["role_id"] == 1) ? "readonly" : "" ; ?> >
								<noscript>
									<button>Save</button>
								</noscript>
							</form>
						</td>
						<th><a href="login-log.php?email=<?php echo urlencode($user["email"]) ?>"><span class="glyphicon glyphicon-book"></span> Login History</a></th>
						<td>
							<?php if ($user["role_id"] == 2): ?>
							<div class="btn-group btn-group-sm">
								<a href="edit-company.php?user_id=<?php echo $user["id"] ?>" title="Edit" data-user-id="<?php echo $user["id"] ?>" class="btn edit-user btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit </a>
								<button title="Delete" type="button" data-user-id="<?php echo $user["id"] ?>" class="btn delete-user-btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
							</div>
							<?php else: ?>
							<span class="label label-default">
								<?php foreach ($roles as $r): ?>
									<?php echo ($r["id"] == $user["role_id"]) ? $r["role"] : "" ; ?>
								<?php endforeach ?>
							</span>
							<?php endif ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
<?php require_once("includes/footer.php"); ?>

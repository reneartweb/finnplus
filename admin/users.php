<?php 

define('ALLOW_ACCESS', true); // allow access to this page
require_once("includes/header.php"); 
$db = new Database;

$db->query('SELECT id, role FROM user_roles ORDER BY id DESC');
$roles = $db->fetchAll();

$db->query('SELECT id, eng_name FROM languages ORDER BY id DESC');
$languages = $db->fetchAll();

if (isset($_GET["id"])) {
	$db->query('SELECT id, name, role_id, email, phone, date_registered, birthday, role_id, lang_id, can_login FROM users
				WHERE id LIKE :id AND name LIKE :name AND role_id LIKE :role_id AND email LIKE :email AND phone LIKE :phone AND date_registered LIKE :date_registered AND birthday LIKE :birthday AND role_id LIKE :role_id ORDER BY id DESC');

	$db->bind(':id', "%".$_GET["id"]."%");
	$db->bind(':name', "%".$_GET["name"]."%");
	$db->bind(':role_id', "%".$_GET["role_id"]."%");
	$db->bind(':email', "%".$_GET["email"]."%");
	$db->bind(':phone', "%".$_GET["phone"]."%");
	$db->bind(':date_registered', "%".$_GET["date_registered"]."%");
	$db->bind(':birthday', "%".$_GET["birthday"]."%");
	$db->bind(':role_id', "%".$_GET["role_id"]."%");

	$users = $db->fetchAll();
} else {
	$db->query('SELECT id, name, role_id, email, phone, date_registered, birthday, role_id, lang_id, can_login FROM users WHERE role_id = 2 AND (id NOT IN (SELECT user_id from companies)) ORDER BY id DESC');
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
					<th>NAME</th>
					<th>AD's</th>
					<th>EMAIL</th>
					<th>PHONE</th>
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
							<td><a title="View User Ad's" href="advertisments.php?owner=<?php echo $user["id"] ?>"><?php echo $user["name"] ?></a></td>
						<?php else: ?>
							<td title="No Ad's"><?php echo $user["name"] ?></td>
						<?php endif ?>
						<td>
							<?php if ($p["count"] > 0): ?>
								<a title="View User Ad's" href="advertisments.php?owner=<?php echo $user["id"] ?>">
									<span class="badge"><?php echo $p["count"];?></span>
								</a>
							<?php else: ?>
									<span class="badge">Zero</span>						
							<?php endif ?>
						</td>
						<td><?php echo $user["email"] ?></td>
						<td><?php echo $user["phone"] ?></td>
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
								<a href="edit-user.php?user_id=<?php echo $user["id"] ?>" title="Edit" data-user-id="<?php echo $user["id"] ?>" class="btn edit-user btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit </a>
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

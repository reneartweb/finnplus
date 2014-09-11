<?php 

define('ALLOW_ACCESS', true); // allow access to this page
require_once("includes/header.php"); 

$db = new Database;
$db->query('SELECT id, name, role_id, email, phone, date_registered, birthday, lang_id, can_login FROM users WHERE role_id != 2 ORDER BY id DESC');
$users = $db->fetchAll();

$db->query('SELECT id, role FROM user_roles ORDER BY id DESC');
$roles = $db->fetchAll();

$db->query('SELECT id, eng_name FROM languages ORDER BY id DESC');
// $db->bind(':product_id', $product_id);
$languages = $db->fetchAll();


?>

<h1 class="page-header">Employees <small><span class="label label-default"><?php echo count($users) ?></span></small></h1>

<div class="table-responsive">
	<table class="table table-hover table-condensed">
		<thead>
			<tr>
				<th>NAME</th>
				<th>EMAIL</th>
				<th>PHONE</th>
				<th>REG_DATE</th>
				<th>BIRTHDAY</th>
				<th>LANG</th>
				<th>ROLE</th>
				<th>CAN_LOGIN</th>
				<th></th>
				<th></th>
				<th style="min-width:101px;"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($users as $user): ?>
			<tr>
				<td><?php echo $user["name"] ?></td>
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
					<span class="label label-<?php echo ($user["role_id"] == 1) ? "default" : "success" ; ?>">
						<?php foreach ($roles as $r): ?>
							<?php echo ($r["id"] == $user["role_id"]) ? $r["role"] : "" ; ?>
						<?php endforeach ?>
					</span>
				</td>
				<td>
					<input type="checkbox" name="can-login" class="bootstrapSwitch"  <?php echo ($user["can_login"]) ? "checked" : "" ; ?> <?php echo ($user["role_id"] == 1) ? "readonly" : "" ; ?> >
				</td>
				<td><a href="login-log.php?email=<?php echo urlencode($user["email"]) ?>" title="Login History"><span class="glyphicon glyphicon-book"></span></a></td>
				<td><a href="admin-log.php?employee=<?php echo urlencode($user["id"]) ?>" title="Employee Activity"><span class="glyphicon glyphicon-eye-open"></span></a></td>
				<td>
					<?php if ($user["role_id"] != 1): ?>
					<div class="btn-group btn-group-sm">
						<a href="edit-user.php?user_id=<?php echo $user["id"] ?>" title="Edit" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit </a>
						<button title="Delete" type="button" data-user-id="<?php echo $user["id"] ?>" class="btn delete-user btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
					</div>							
					<?php endif ?>
				</td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
</div>

<?php require_once("includes/footer.php"); ?>

<?php 

define('ALLOW_ACCESS', true); // allow access to this page
require_once("includes/header.php"); 
$db = new Database;

// get all languages for select
$db->query('SELECT id, eng_name FROM languages ORDER BY id DESC');
$languages = $db->fetchAll();

// get all roles for select
$db->query('SELECT id, role FROM user_roles ORDER BY id DESC');
$roles = $db->fetchAll();

if (isset($_POST["email"]) && isset($_GET["user_id"])) {
	$db->query("UPDATE users SET name=:name ,role_id=:role_id ,email=:email ,phone=:phone ,date_registered=:date_registered ,birthday=:birthday ,lang_id=:lang_id ,can_login=:can_login WHERE id=:id");
	$db->bind(':id', $_GET["user_id"]);
	$db->bind(':name', $_POST["name"]);
	$db->bind(':role_id', $_POST["role_id"]);
	$db->bind(':email', $_POST["email"]);
	$db->bind(':phone', $_POST["phone"]);
	$db->bind(':date_registered', $_POST["date_registered"]);
	$db->bind(':birthday', $_POST["birthday"]);
	$db->bind(':lang_id', $_POST["lang_id"]);
	$db->bind(':can_login', $_POST["can_login"]);
	$update_user = $db->execute();

	$message = "Changed user ".$_GET["user_id"]." information to:";
	$message .= "name = ".$_POST["name"];
	$message .= ", role_id = ".$_POST["role_id"];
	$message .= ", email = ".$_POST["email"];
	$message .= ", phone = ".$_POST["phone"];
	$message .= ", date_registered = ".$_POST["date_registered"];
	$message .= ", birthday = ".$_POST["birthday"];
	$message .= ", lang_id = ".$_POST["lang_id"];
	$message .= ", can_login = ".$_POST["can_login"];

	$db->insertAdminLog( $_SESSION["employee"], $message, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], session_id() );
}

if (isset($_GET["user_id"])) {
	$db->query('SELECT name, role_id, email, phone, date_registered, birthday, lang_id, can_login FROM users WHERE id=:user_id AND role_id != 1 LIMIT 1');
	$db->bind(':user_id', $_GET["user_id"]);
	$user = $db->single();
}



?>

<h1 class="page-header">Edit User </h1>

<?php if (isset($_POST["email"]) && $update_user ): ?>
	<?php if ($update_user): ?>
		<div class="alert alert-success alert-dismissable">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		  <strong>Saved!</strong> Changing user details was successful.
		</div>
	<?php else: ?>
		<div class="alert alert-warning alert-dismissable">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		  <strong>Warning!</strong> Something went wrong, please try again.
		</div>	
	<?php endif ?>
<?php endif ?>



<div id="container" class="well well-lg">
<?php if ($user): ?>
	<form class="form-horizontal edit-user-form" role="form" method="post" action="">
	  <?php foreach ($user as $key => $value): ?>
		  <div class="form-group">
		    <label for="<?php echo $key ?>" class="col-sm-2 control-label"><?php echo $key ?></label>
		    <div class="col-sm-10">
		    	<?php if ($key == "lang_id"): ?>
		    		<select class="form-control" name="lang_id" id="lang_id">
		    			<?php foreach ($languages as $lang): ?>
		    				<option value="<?php echo $lang["id"] ?>" <?php echo ($lang["id"] == $value) ? "selected" : ""; ?>><?php echo $lang["eng_name"] ?></option>
		    			<?php endforeach ?>
		    		</select>
		    	<?php elseif ($key == "role_id"): ?>
		    		<select class="form-control" name="role_id" id="role_id">
		    			<?php foreach ($roles as $role): ?>
		    				<option value="<?php echo $role["id"] ?>" <?php echo ($role["id"] == $value) ? "selected" : ""; ?> <?php echo ($role["id"] == 1) ? "disabled" : ""; ?>><?php echo $role["role"] ?></option>
		    			<?php endforeach ?>
		    		</select>
		    	<?php elseif ($key == "can_login"): ?>
					<select class="form-control" name="can_login" id="can_login">
						<option value="0" <?php echo ($value == 0) ? "selected" : "" ; ?>>No</option>
						<option value="1" <?php echo ($value == 1) ? "selected" : "" ; ?>>Yes</option>
					</select>
				<?php else: ?>
		      		<input type="text" name="<?php echo $key ?>" class="form-control" id="<?php echo $key ?>" placeholder="<?php echo $key ?>" required value="<?php echo $value ?>">
				<?php endif ?>
		    </div>
		  </div>		
	  <?php endforeach ?>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span> Save Changes</button>
	    </div>
	  </div>
	</form>
<?php else: ?>
	<p>No user found</p>
<?php endif ?>

</div>

<?php require_once("includes/footer.php"); ?>
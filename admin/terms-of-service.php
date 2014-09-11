<?php 

define('ALLOW_ACCESS', true); // allow access to this page
require_once("includes/header.php"); 
$db = new Database;

$path = "../lib/text-files/terms-and-conditions/";

if (isset($_GET["lang"])) {
	$lang = $_GET["lang"];	
} else {
	$lang = "eng";
}

if (isset($_POST["privacy-policy"])) {
	file_put_contents($path.$lang."/privacy-policy.txt", $_POST["privacy-policy"]);
	file_put_contents($path.$lang."/terms-of-service.txt", $_POST["terms-of-service"]);
} 



?>

<h1 class="page-header">Terms of Service</h1>
<div class="btn-group btn-group-justified">
  <a href="?lang=eng" class="btn btn-info <?php echo ($lang == "eng") ? "active" : ""; ?> ">English</a>
  <a href="?lang=nor" class="btn btn-info <?php echo ($lang == "nor") ? "active" : ""; ?> ">Norwegian</a>
  <a href="?lang=dk"  class="btn btn-info <?php echo ($lang == "dk")  ? "active" : ""; ?> ">Danish</a>
</div>
<hr>
<form id="terms-and-conditions-form" action="" role="form" method="post">
	<div class="row">
		<div class="col-md-6">
			<label for="privacy-policy">Privacy Policy</label>
			<textarea class="form-control" name="privacy-policy" id="privacy-policy" rows="18"><?php echo file_get_contents($path.$lang."/privacy-policy.txt"); ?></textarea>
		</div>
		<div class="col-md-6">
			<label for="terms-of-service">Terms Of Service</label>
			<textarea class="form-control" name="terms-of-service" id="terms-of-service" rows="18"><?php echo file_get_contents($path.$lang."/terms-of-service.txt"); ?></textarea>
		</div>
	</div>
	<hr>
	<button class="btn btn-success btn-block btn-lg" type="submit">Save Changes</button>
</form>
<?php require_once("includes/footer.php"); ?>

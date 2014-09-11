<?php 

define('ALLOW_ACCESS', true); // allow access to this page
require_once("includes/header.php"); 
// $db = new Database;

$path = "../lib/translations/";

if (isset($_GET["lang"])) {
	$lang = $_GET["lang"];	
} else {
	$lang = "eng";
}

$languages = array();

$directory = scandir($path);
$translation_files = array_diff($directory, array('..', '.'));
foreach ($translation_files as $i => $file) {
	$parts=pathinfo($file);
	if ($parts['extension'] == "txt") {
		array_push($languages, $parts['filename']);
	}
}

if (isset($_POST["new_lang_slug"])) {
	unset($_SESSION["translation_array"]);
	$new_translation = true;
	foreach ($translation_files as $i => $file) {	
		$parts=pathinfo($file);
		if ($parts['extension'] == "txt") {
			$content = unserialize(file_get_contents($path.$file));
			if (!isset($content[$_POST["new_lang_slug"]])) {
				$content[$_POST["new_lang_slug"]] = "";
				file_put_contents($path.$file, serialize($content));
			} else {
				$new_translation = false;
			}
		}
	} 

	if ($new_translation) { ?>
		<p class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $_POST["new_lang_slug"]; ?> successfully added to language files.
		</p>
		<?php
	} else { ?>
		<p class="alert alert-warning alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $_POST["new_lang_slug"]; ?> already existed in the language files.
		</p>
		<?php
	}
}

$translations = array();
?>

<h1 class="page-header">Translations</h1>
<!-- <div class="btn-group btn-group-justified">
	<?php foreach ($languages as $language): ?>
	  <a href="?lang=<?php echo $language; ?>" class="btn btn-info <?php echo ($lang == $language) ? "active" : ""; ?> "><?php echo strtoupper($language); ?></a>
	<?php endforeach ?>
</div>
<hr> -->

	<div class="table-responsive well well-lg">
		<table class="table">
			<thead>
				<tr>
					<th>Translation Slug</th>
					<?php foreach ($languages as $language): ?>
						<?php 
							// get the $translation array from the "language".txt file
							$translation = unserialize(file_get_contents($path.$language.".txt"));
							// sort translation array
							ksort($translation);
							// construct a new array with the language as the key and the translation array as the value
							$translations[$language] = $translation;
						?>
						<th><?php echo $language; ?></th>
					<?php endforeach ?>
				</tr>
			</thead>
			<tbody>
			<?php // get all the slugs from ... for example English language ?>
			<?php foreach ($translations["eng"] as $slug => $string): ?>
			<form class="form-horizontal translate-form" action="ajax/change-translation.php" role="form" method="post">
				<tr>
					<td><?php echo $slug; ?></td>
					<?php foreach ($languages as $language): ?>
						<td>
							<input name="<?php echo $language; ?>[<?php echo $slug; ?>]" type="text" class="form-control" value="<?php echo $translations[$language][$slug]; ?>">
						</td>
					<?php endforeach ?>
					<td>
						<div class="btn-group btn-block btn-group-sm">
						  <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
						  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						    <span class="caret"></span>
						    <span class="sr-only">Toggle Dropdown</span>
						  </button>
						  <ul class="dropdown-menu" role="menu">
						    <li><a class="delete-translation" data-name="eng[<?php echo $slug; ?>]" data-value="<?php echo $translations['eng'][$slug]; ?>" href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
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
	
<hr>
<form action="" class="form-horizontal well" role="form" method="post">
	<div class="form-group">
		<label for="new_lang_slug" class="col-sm-2 control-label">New_Translations_Slug</label>
		<div class="col-sm-4">
		  	<input id="new_lang_slug" type="text" class="form-control" name="new_lang_slug" placeholder="small letters & no spaces !">
		</div>
		<div class="col-sm-2">
			<button class="btn btn-success btn-block" type="submit">Add</button>			
		</div>
	</div>
</form>


<?php require_once("includes/footer.php"); ?>
<?php 

define('ALLOW_ACCESS', true); // allow access to this page
require_once("includes/header.php"); 
// $db = new Database;

$path = "../lib/text-files/bad-words.txt";

if (isset($_POST["bad-words"])) {
	// get the value from the form
	$bad_words = $_POST["bad-words"];
	// create an array with all the words
	$bad_words = explode(",", $bad_words);
	foreach ($bad_words as $i => $word) {
		// remove (trim) leading and trailing whitespace
		$bad_words[$i] = trim($word);
	}
	// remove duplicate words
	$bad_words = array_unique($bad_words);
	// filter out any empty elements
	$bad_words = array_filter($bad_words);	
	// sort the words alphabetically
	asort($bad_words);
	// create one big string where the separated words are joined by ", "
	$bad_words = join(", ",$bad_words);
	// save the string into the document as lower
	file_put_contents($path, strtolower($bad_words));
}  else {
	// get the file content
	$bad_words = file_get_contents($path);
}

$count = count(explode(", ", $bad_words));

?>

<h1 class="page-header">Bad Words <small class="badge"><?php echo $count; ?></small></h1>
<p class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	Words need to be coma (,) separated!
</p>
<form action="" role="form" method="post">
	<textarea class="form-control" name="bad-words" rows="18"><?php echo $bad_words; ?></textarea>
	<hr>
	<button class="btn btn-success btn-block btn-lg" type="submit">Save Changes</button>
</form>

<?php require_once("includes/footer.php"); ?>
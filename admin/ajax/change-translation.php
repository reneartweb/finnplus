<?php 
require_once "../../lib/includes/session.php";
$path = "../../lib/translations/";
$return = "Not Saved";
if (isset($_POST["eng"])) {
	$origTranslations = array();
	$return = "";
	foreach ($_POST as $language => $translations) {
		$return .= " ".strtoupper($language).": ";

		if (file_exists($path.$language.".txt")) {
			$origTranslations[$language] = unserialize(file_get_contents($path.$language.".txt"));
			foreach ($translations as $key => $value) {
				if (!empty($value)) {
					$origTranslations[$language][$key] = $value;
					// $origTranslations[$language] = array_merge($origTranslations[$language], $_SESSION["translation_array"][$language]);
					// if ($value == 'DELETE_') { 
					// 	unset($origTranslations[$language][$key]); 
					// }
					$return = $return . $value;
				}
			}
	
			// if ($language == "eng") {
			// 	$db = new Database;
			// 	$origTranslations[$language] = array_merge($origTranslations[$language], $db->getAllCategories());
			// }
			if (!file_put_contents($path.$language.".txt", serialize($origTranslations[$language]))) {
				$return = "Not ";
			}
		}
	}
	$return .= " Saved";
	unset($_SESSION["translation_array"]);
	$_SESSION["translation_array"] = $origTranslations;
}

echo $return;
?>
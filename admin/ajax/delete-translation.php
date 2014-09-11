<?php 
require_once "../../lib/includes/session.php";
$path = "../../lib/translations/";
$return = "Not DELETED";
if (isset($_POST["eng"])) {
	$origTranslations = array();
	$return = "";
	foreach ($_POST as $language => $translations) {
		if (file_exists($path.$language.".txt")) {
			$origTranslations[$language] = unserialize(file_get_contents($path.$language.".txt"));
			foreach ($translations as $key => $value) {
				if (!empty($value)) {
					unset($origTranslations[$language][$key]); 
					$return = $return . $value;
				}
			}
			if (!file_put_contents($path.$language.".txt", serialize($origTranslations[$language]))) {
				$return = "Not ";
			}
		}
	}
	$return .= " DELETED";
	unset($_SESSION["translation_array"]);
	$_SESSION["translation_array"] = $origTranslations;
}
echo $return;
?>
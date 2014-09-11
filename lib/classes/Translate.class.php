<?php 

class Translate {

	public function string($slug) {
		if(!isset($_SESSION)) {
			session_start();
		}
		$language = $_SESSION["lang"] ? $_SESSION["lang"] : "eng";
		// $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') ? 'https://' : 'http://';
		// $path = $protocol.$_SERVER['SERVER_NAME']."/lib/translations/";
		if (!isset($_SESSION["translation_array"][$language])) {
			$file = "lib/translations/".$language.".txt";
			$content = file_get_contents($file);
			$_SESSION["translation_array"][$language] = unserialize($content);
		}
		$translation = $_SESSION["translation_array"][$language][$slug];
		if (empty($translation)) {
			$_SESSION["translation_array"][$language][$slug] = $slug;
			return "#".$slug."#".$language."#";
		}
		return $translation;
	}

} // end of class
 ?>
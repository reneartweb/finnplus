<?php 

$array = false;
if (isset($_POST)) { 
	$array = $_POST;
	$type = "_POST";
}
if (isset($_GET)) { 
	$array = $_GET;
	$type = "_GET";
}

if ($array) {
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			foreach ($value as $key1 => $value1) {
				if (is_array($value1)) {
					foreach ($value1 as $key2 => $value2) {
						$array[$key][$key1][$key2] = strip_tags($value2);
					}
				} else {
					$array[$key][$key1] = strip_tags($value1);					
				}
			}
		} else {
			$array[$key] = strip_tags($value);
		}
	}
	if ($type == "_POST") {
		unset($_POST);
		$_POST = $array;
	} else {
		unset($_GET);
		$_GET = $array;		
	}
}

?>
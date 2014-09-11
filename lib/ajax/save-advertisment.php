<?php 

	require_once "../includes/session.php";
	require_once "../includes/sanitize-all.php";
	spl_autoload_register(function ($class) { require_once "../classes/".$class.".class.php"; });
	$_POST = array_filter($_POST); 	// remove empty elements of array

	// check if user is logged in
	if (empty($_SESSION["user_id"]) or !User::isLoggedIn()) die(Translate::string("save_advertisement.please_login"));

	$user = new User();
	$user = $user->getUser($_SESSION["user_id"]);
	if (!$user) die(Translate::string("save_advertisement.no_such_user"));
	

	$required = array(
		Translate::string("save_advertisement.forgot_to_1") => 'advert-img', 
		Translate::string("save_advertisement.forgot_to_2") => 'main-category',
		Translate::string("save_advertisement.forgot_to_3") => 'subCategory',
		Translate::string("save_advertisement.forgot_to_4") => 'title',
		Translate::string("save_advertisement.forgot_to_5") => 'price',
		Translate::string("save_advertisement.forgot_to_6") => 'currencyID',
		Translate::string("save_advertisement.forgot_to_7") => 'paymentMethod',
		Translate::string("save_advertisement.forgot_to_8") => 'zip',
		Translate::string("save_advertisement.forgot_to_9") => 'city_name',
		Translate::string("save_advertisement.forgot_to_10") => 'country_code',
		Translate::string("save_advertisement.forgot_to_11") => 'languageID',
		Translate::string("save_advertisement.forgot_to_12") => 'description',
		);


	// ======================
	// CHECKING FOR BAD WORDS
	// ======================

	$bad_words_string = file_get_contents("../text-files/bad-words.txt");
	$bad_words_array = explode(",", $bad_words_string);
	foreach ($bad_words_array as $i => $word) {
		// remove (trim) leading and trailing whitespace
		$bad_words_array[$i] = trim($word);
	}
	// filter out any empty elements
	$bad_words_array = array_filter($bad_words_array);


	// Check if all the required inputs are present
	foreach ($required as $key => $input_name) {
		if (!isset($_POST[$input_name]) or empty($_POST[$input_name])) die(Translate::string("save_advertisement.forgot_to_0")." ".$key);
		// check for bad words
		if (is_string($_POST[$input_name])) {
			$word = trim(strtolower($_POST[$input_name]));
			if ( strpos($bad_words_string, " ".$word.",") ) die(sprintf(Translate::string("save_advertisement.remove_bad_word_1"), "<em>".strtoupper($word)."</em>", str_replace("_", " ", $input_name)));
		}
		if ( strpos($bad_words_string, " ".$input_name.",") ) die(sprintf(Translate::string("save_advertisement.remove_bad_word_2"), "<em>".strtoupper($input_name)."</em>"));
	}

	if (empty($_POST["description"])) {
		$description = "-";
	} else {
		$description = $_POST["description"];
	}

	// Check description
	foreach ($bad_words_array as $i => $word) {
		if (strpos(strtolower($description), " ".$word." ") ) die(sprintf(Translate::string("save_advertisement.remove_bad_word_3"), "<em>".strtoupper($word)."</em>"));
	}

	// Construct an array for Extra Details
	$details = array();
	foreach ($_POST as $key => $value) {
		if (!in_array($key, $required) && !empty($value)) {
			if (is_array($value)) { 
				$value = array_filter($value); // remove empty elements of array
				foreach ($value as $key1 => $value1) { // check for bad words
					if (is_array($value1)) {
						$value1 = array_filter($value1); // remove empty elements of array
						foreach ($value1 as $key2 => $value2) {
							if (strpos($bad_words_string, " ".strtolower($value2)."," ) ) die(sprintf(Translate::string("save_advertisement.remove_bad_word_4"), "<em>".strtoupper($value2)."</em>"));
						}
					} else {
						if (strpos($bad_words_string, " ".strtolower($value1)."," ) ) die(sprintf(Translate::string("save_advertisement.remove_bad_word_5"), "<em>".strtoupper($value1)."</em>"));						
					}
				}
			} else {
				if (strpos($bad_words_string, " ".strtolower($value)."," ) ) die(sprintf(Translate::string("save_advertisement.remove_bad_word_6"), "<em>".strtoupper($value)."</em>"));
			}
			$details[$key] = $value;
		}
	}

	// ======================
	// SAVE AD TO DATABASE
	// ======================

	$advertisment = new Product($user, $_POST["subCategory"], $_POST["title"], $_POST["price"], $_POST["currencyID"], $_POST["paymentMethod"], $_POST["zip"], $_POST["city_name"], $_POST["country_code"], $_POST["languageID"], $description, $details, $_POST["advert-img"] );
	$advertisment->insertToDB();

	?>
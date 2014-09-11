<?php 

class Validate { 

	// Methods
	public function isDate($date) {
		// Accepts a string with the date format YYYY-MM-DD
		$date_array = explode("-", $date);
		$year = $date_array[0];
		$month = $date_array[1];
		$day = $date_array[2];
		$today = date("Y-m-d");
		if ($date > $today) {
			throw new Exception("Date can not be in the future", 1);
		} else if ( is_string($date) // accepts only strings (no array or int)
			&& intval($year) > 1900  // year is always bigger than 1900
			&& strlen($date) == 10   // date has specific lenght of 10 characters
			&& checkdate($month, $day, $year) )
		{
			return true;
		} else {
			return false;
		}
	}

	public function isString($something, $minLenght = 0, $maxLenght = 1000) {
		// accepts a string and the optional minLenght and maxLenght of the string
		if ( is_string($something) && strlen($something) > $minLenght && strlen($something) < $maxLenght ) {
			return true;
		} else {
			return false;
		}
	}

}

?>
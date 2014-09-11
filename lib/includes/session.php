<?php 

	if(!isset($_SESSION)) {
		session_start();
		
		ini_set('session.cookie_httponly', 'On');
		ini_set('session.cookie_secure', 'On');
		ini_set('session.use_cookies', 'On');
		ini_set('session.use_only_cookies', 'On');

	}

 ?>
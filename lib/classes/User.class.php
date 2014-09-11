<?php 

class User { 

	// Properties
	protected $_id;
	protected $_name;
	protected $_email;
	protected $_password;
	protected $_date_registred;
	protected $_role_id;
	protected $_birthday;
	protected $_phone;
	protected $_lang_id;

	public $company_name;
	public $company_number;
	public $company_address;
	public $company_zip;
	public $phone_2;

	// Constructor
	public function __construct() {
	}

	/* 
	* ======================
	* === Custom Methods ===
	* ======================
	*/

	public function resetPassword($email, $password)
	{
		$this->setPassword( $password );
		$db = new Database();
		$db->query('UPDATE users SET password = :password WHERE email = :email');
		$db->bind( ':email', $email );
		$db->bind( ':password', $this->password() );
		$reset = $db->execute();
		if ($reset) {
			return true;
		}
		return false;
	}


	public function registerUser ( $name, $role_id, $email, $password, $phone, $lang_id, $birthday , $company = false) {
		// Accepts 5 arguments in the array: name, email, password, phone, lang_id, birthday (optional)
		// $settings = array(string $name, string $email, string $phone, int $lang_id [, date(YYYY-MM-DD) $birthday ] ); 

		// setting values from settings array
		$date_registered = date("Y-m-d");

		// checking if email exists 
		if ( $this->userEmailExist($email) ) {
			throw new Exception(Translate::string("user.email_already_exists"), 1);
		} else {
			$db = new Database();

			$db->beginTransaction();

			// setting the user properties and validating
			$this->setName( $name );
			$this->setRole( $role_id );
			$this->setEmail( $email );
			$this->setPassword( $password );
			$this->setPhone( $phone );
			$this->setDateRegistred( $date_registered );
			$this->setLangID( $lang_id );
			$this->setBirthday( $birthday );

			$db->query('INSERT INTO users ( name, role_id, email, password, phone, date_registered, lang_id, birthday) 
								   VALUES (:name,:role_id,:email,:password,:phone,:date_registered,:lang_id,:birthday) ');
			
			$db->bind( ':name', $this->name() );
			$db->bind( ':role_id', $this->role() );
			$db->bind( ':email', $this->email() );
			$db->bind( ':password', $this->password() );
			$db->bind( ':phone', $this->phone() );
			$db->bind( ':date_registered', $this->dateRegistred() );
			$db->bind( ':lang_id', $this->langID() );
			$db->bind( ':birthday', $this->birthday() );

			$db->execute();
			$newUserID = $db->lastInsertId();

			if ($company) {
				$db->query('INSERT INTO companies ( user_id, company_name, company_number, company_address, company_zip, phone_2) 
										   VALUES (:user_id, :company_name,:company_number,:company_address,:company_zip,:phone_2) ');
			
				$db->bind( ':user_id', $newUserID );
				$db->bind( ':company_name', $this->company_name );
				$db->bind( ':company_number', $this->company_number );
				$db->bind( ':company_address', $this->company_address );
				$db->bind( ':company_zip', $this->company_zip );
				$db->bind( ':phone_2', $this->phone_2 );
				$db->execute();
			}

			$db->endTransaction();

			$this->setID( $newUserID );
			$this->checkCredentials($this->email(), $password, 1, $_SERVER["HTTP_USER_AGENT"], $_SERVER["REMOTE_ADDR"], session_id() );
		}
	}

	public function isLoggedIn()
	{
		if (!isset($_SESSION)) return false;
		$last_login = date("Y-m-d H:i:s", strtotime("-24 hours"));
		
		if (empty($_SESSION["email"])) { return false; }
		
		$db = new Database();
		$db->query('SELECT id FROM log_login WHERE session_id = :session_id AND ip_address = :ip_address 
								AND email=:email AND date_time > :last_login AND status = "success" LIMIT 1');
		$db->bind( ':email', $_SESSION["email"] );
		$db->bind( ':last_login', $last_login );
		$db->bind( ':session_id', session_id() );
		$db->bind( ':ip_address', $_SERVER['REMOTE_ADDR'] );
		return $db->single();
	}


	// check if username and password match
	public function checkCredentials($email, $password, $javascript, $browser, $ip, $session_id)
	{
		try {
			$minutes = rand(15, 45);
			$time_limit = date("Y-m-d H:i:s", strtotime("-".$minutes." min"));
			$db = new Database();

			$db->query('SELECT id FROM log_login WHERE email=:email AND date_time > :time_limit AND status != "success" LIMIT 11');
			$db->bind( ':email', $email );
			$db->bind( ':time_limit', $time_limit );
			$login_attempts = $db->fetchAll();

			// check if user has less then 10 failed attempts to login
			if (count($login_attempts) >= 5) {  
				// !!!!!!!!!!!!! sent email with token to user to get direct acces to account but check also if ip addresses are same and insert new column to tokens db_tabel named ip_address
				throw new Exception(Translate::string("user.blocked"), 1);
				return false;
			}

			// check passwords
			$db->query('SELECT id, password, can_login, role_id FROM users WHERE email=:email LIMIT 1');
			$db->bind( ':email', $email );
			$user = $db->single();

			if ($user) {
				if (!$user["can_login"]) {
			   		$this->insertLog("user tried to login but was blocked by admin",$email, $javascript, $browser, $ip, $session_id);
					throw new Exception(Translate::string("user.disabled"), 1);
					return false;
				}
				$db_pass = $user["password"];
				$password_hashed = hash("sha256", $password);
				
		    	if ($db_pass === $password_hashed) {
		    		// passwords match and login successful
					if(!isset($_SESSION)) {
						session_start();
					}
					ini_set('session.cookie_httponly', 'On');
					ini_set('session.cookie_secure', 'On');
					ini_set('session.use_cookies', 'On');
					ini_set('session.use_only_cookies', 'On');					
					ini_set("session.cookie_lifetime","1800"); // half hour
					$_SESSION['timeout'] = time()+1800;
					$_SESSION["email"] = $email;
					$_SESSION['user_id'] = $user["id"];
					if ($user["role_id"] == 1 || $user["role_id"] == 3) {
						$_SESSION['employee'] = $user["id"];
						$employee = true;
						if ($user["role_id"] == 1) $admin = true;
					}
					$admin = false;
					$employee = false;
		    		$this->insertLog("success",$email, $javascript, $browser, $ip, $session_id);
					return true;
				}
			}

			// login failed
	   		$this->insertLog("failure",$email, $javascript, $browser, $ip, $session_id);
			throw new Exception(Translate::string("login.wrong_credentials"), 1);
			return false;
		} catch (Exception $e) {
			echo '' .$e->getMessage();
		}
	}

	public function insertLog( $status, $email, $javascript, $browser, $ip, $session_id )
	{
		try {
			$db = new Database();
			$db->query('INSERT INTO log_login ( email, date_time, ip_address, session_id, javascript, browser, status) 
											 VALUES (:email,:date_time,:ip_address,:session_id,:javascript,:browser, :status) ');
				
			$db->bind( ':email', $email );
			$db->bind( ':date_time', date("Y-m-d H:i:s") );
			$db->bind( ':ip_address', $ip );
			$db->bind( ':session_id', $session_id );
			$db->bind( ':javascript', $javascript );
			$db->bind( ':browser', $browser );
			$db->bind( ':status', $status );

			$insert = $db->execute();

			if (!$insert) { // inserting was succesfull
				throw new Exception("Error Processing Login Log Insert", 1);
			}
		} catch (Exception $e) {
			echo 'Error: ' .$e->getMessage();			
		}
	}

	public function getUser($id)
	{
		// checking if the user exist in database
		$db = new Database();
		$db->query('SELECT ID, name, role_id, email, phone, date_registered, birthday, lang_id FROM users WHERE id=:id  LIMIT 1');		
		$db->bind( ':id', $id );
		$user = $db->single();

		if ($user) {
			$this->setID( $user["ID"] );
			$this->setName( $user["name"] );
			$this->setRole( $user["role_id"] );
			$this->setEmail( $user["email"] );
			$this->setPhone( $user["phone"] );
			$this->setDateRegistred( $user["date_registered"] );
			$this->setBirthday( $user["birthday"] );
			$this->setLangID( $user["lang_id"] );
			return $this;
		}
		return false;
	}

	public function logout()
	{	
		session_regenerate_id();
		session_destroy();
	}

	public function unsetSession()
	{
		unset($_SESSION["user_id"]);
		unset($_SESSION["email"]);
		unset($_SESSION['name']);
		unset($_SESSION['role_id']);
		unset($_SESSION['phone']);
		unset($_SESSION['date_registered']);
		unset($_SESSION['birthday']);
		unset($_SESSION['lang_id']);
	}

	public function userEmailExist($email)
	{
		// checking if the user exist in database
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		$db = new Database();
		$db->query('SELECT ID FROM users WHERE email=:email  LIMIT 1');		
		$db->bind( ':email', $email );
		$result = $db->single();

		if ($result) {
			return $result;
		}
		return false;
	}

	// ==============================
	// =========== TOKENS ===========
	// ==============================

	public function isTokenValid($email, $token)
	{
		if (!self::userEmailExist($email)) {
			return false;
		}
		$today = date("Y-m-d H:i:s");
		$db = new Database();
		$db->query('SELECT ID FROM tokens WHERE expiration_datetime > :today AND email=:email AND token=:token  LIMIT 1');		
		$db->bind( ':today', $today );
		$db->bind( ':email', $email );
		$db->bind( ':token', $token );
		$result = $db->single();

		if ($result) {
			return true;
		}
		return false;
	}

	public function insertToken($email)
	{
		if (!self::userEmailExist($email)) {
			return false;
		}
		$raw_token 	= self::randomPassword(8); // generate a random token from 8 charackers
		$token 		= hash("sha256", $raw_token); // encrypt the token
		$expiration_datetime = date("Y-m-d H:i:s", strtotime("+24 hours"));

		$db = new Database();
		$db->query('INSERT INTO tokens ( email, token, expiration_datetime) 
										  VALUES (:email,:token,:expiration_datetime) ');
		$db->bind( ':email', $email );
		$db->bind( ':token', $token );
		$db->bind( ':expiration_datetime', $expiration_datetime );
		$insert = $db->execute();
		if ($insert) { // inserting was succesfull
			return $token;
		}
		return false;
	}
	
	public function destroyToken($token)
	{
		$now = date("Y-m-d H:i:s");
		$db = new Database();
		$db->query('UPDATE tokens SET expiration_datetime = :now WHERE token = :token');
		$db->bind( ':now', $now );
		$db->bind( ':token', $token );
		$update = $db->execute();

		if ($update) {
			return true;
		}
		return false;
	}

	private function randomPassword($length) { // Source: http://stackoverflow.com/questions/6101956/generating-a-random-password-in-php
	    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	    $pass = array();
	    $alphaLength = strlen($alphabet) - 1;
	    for ($i = 0; $i < $length; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass);
	}

	// =============================
	// ===== GETTERS & SETTERS =====
	// =============================
	
	public function id() { return $this->_id; }
	public function setID($id) {
		// if (is_int($id)) {
			$this->_id = $id;
		// } else {
		// 	throw new Exception("Error Processing Request", 1);
		// }
	}

	public function name() { return $this->_name; }
	public function setName($name) {
		if (is_string($name)) {
			$this->_name = $name;
		} else {
			throw new Exception("Error Processing Request", 1);
		}
	}

	public function role() { return $this->_role_id; }
	public function setRole($id) {
		// if (is_int($id)) {
			$this->_role_id = $id;
		// } else {
		// 	throw new Exception("Error Processing Request", 1);
		// }
	}

	public function password() { return $this->_password; }
	public function setPassword($password) {
		// if (is_int($id)) {
			$password_hashed = hash("sha256", $password);
			$this->_password = $password_hashed;
		// } else {
		// 	throw new Exception("Error Processing Request", 1);
		// }
	}

	public function phone() { return $this->_phone; }
	public function setPhone($phoneNr) {
		// if (Validate::isString($phoneNr, 8)) {
			$this->_phone = $phoneNr;		
		// } else {
		// 	throw new Exception("Error Processing Request", 1);
		// }
	}

	public function email() { return $this->_email; }
	public function setEmail($email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->_email = $email;
		} else {
			throw new Exception(Translate::string("login.invalid_email"), 1);
		}
	}

	public function dateRegistred() { return $this->_date_registred; }
	public function setDateRegistred($date) {
		if ( Validate::isDate($date) ) {
			$this->_date_registred = $date;
		} else {
			throw new Exception("Error Processing Request", 1);
		}
	}

	public function birthday() { return $this->_birthday; }
	public function setBirthday($birthday) {
		if ( Validate::isDate($birthday) ) {
			$this->_birthday = $birthday;
		} else {
			throw new Exception(Translate::string("user.wrong_date"), 1);
		}
	}

	public function langID() { return $this->_lang_id; }
	public function setLangID($id) {
		// if (is_int($id)) {
			$this->_lang_id = $id;
		// } else {
		// 	throw new Exception("Error Processing Request", 1);
		// }
	}

	// abstract public function display(); // this means that it forces all the children classes to have a display function
}

?>
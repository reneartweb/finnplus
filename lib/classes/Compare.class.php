<?php 

class Compare { 

	// Properties
	protected $_name;
	protected $_email;
	protected $_date_registred;

	// Constructor
	public function __construct($name, $email) {
		$this->setName($name);
		$this->setEmail($email);
		$this->_date_registred = date("Y-m-d");
	}

	// Methods
	public function registred() { return $this->_date_registred; }

	public function name() { return $this->_name; }
	public function setName($name) {
		if (is_string($name)) {
			$this->_name = $name;
		}
	}

	public function email() { return $this->_email; }
	public function setEmail($email) {
		if (is_string($email) && strpos($email, "@") && strpos($email, ".")) {
			$this->_email = $email;
		}
	}

	// abstract public function display(); // this means that it forces all the children classes to have a display function
}

?>
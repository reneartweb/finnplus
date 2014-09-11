<?php // Tutorial for this class: http://culttt.com/2012/10/01/roll-your-own-pdo-php-class/

// Get the Database configuration
require_once 'db_config.php';

class Database { 

	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $dbname = DB_NAME;

	private $dbh; // database handler
	private $error;
	private $stmt;

	/*
	
	* ========================
	* ==== Custom methods ====
	* ========================

	getProductPriceByID( $productID )
	getSubCatPriceByID( $subCatID )
	getSelectOfAllCategories( $settings = array("") )


	*/

	public function __construct() {
		// Set DSN
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
		// Set options
		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE	 => PDO::ERRMODE_EXCEPTION
		);

		// Create a new PDO instanace
		try {
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		}
		// Catch any errors
		catch(PDOException $e) {
			$this->error = $e->getMessage();
		}
	}

	public function query($query) {
		$this->stmt = $this->dbh->prepare($query);
	}

	public function bind($param, $value, $type = null) {
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}	

	public function execute() {
		return $this->stmt->execute();
	}

	public function fetchAll() {
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function single() {
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function rowCount() {
		return $this->stmt->rowCount();
	}

	public function lastInsertId() {
		return $this->dbh->lastInsertId();
	}

	public function beginTransaction() {
		return $this->dbh->beginTransaction();
	}

	public function endTransaction() {
		return $this->dbh->commit();
	}

	public function cancelTransaction() {
		return $this->dbh->rollBack();
	}

	public function debugDumpParams() {
		return $this->stmt->debugDumpParams();
	}

	// ==================================================================================
	// CUSTOM METHODS
	// ==================================================================================

	public function getMainCategoriesArray( $order = "name ASC" ) {
		$this->query("SELECT id, name FROM categories_main ORDER BY ".$order );
		$results = $this->fetchAll();
		return $results;
	}

	public function getSubCategoriesArray( $mainCatID = 1, $order = "name ASC" ) {
		$this->query("SELECT * FROM categories_sub WHERE main_cat_id = :mainCatID ORDER BY ".$order );
		$this->bind(':mainCatID', $mainCatID );
		$subCategories = $this->fetchAll();
		return $subCategories;
	}


	public function getSelectOfMainCategories( $settings = array() ) {
		// Setting the defaults
		if ( !empty($settings["id"]) ) { $id = $settings["id"]; } else { $id = ""; }
		if ( !empty($settings["class"]) ) { $class = $settings["class"]; } else { $class = ""; }
		if ( !empty($settings["name"]) ) { $name = $settings["name"]; } else { $name = "mainCategory"; }
		if ( !empty($settings["multiple"]) ) { $multiple = $settings["multiple"]; } else { $multiple = ""; }
		if ( !empty($settings["order"]) ) { $order = $settings["order"]; } else { $order = "name ASC"; }
		if ( !empty($settings["inlineCss"]) ) { $inlineCss = $settings["inlineCss"]; } else { $inlineCss = ""; }
		if ( !empty($settings["first-option"]) ) { $first_option = $settings["first-option"]; } else { $first_option = "-- Select Main Category --"; }

		$this->query("SELECT * FROM categories_main ORDER BY ".$order );
		$results = $this->fetchAll(); ?>
		<select name="<?php echo $name; ?>" <?php echo "id='".$id."'"; ?> <?php echo "class='".$class."'"; ?> <?php echo $multiple; ?> <?php if ($inlineCss) { echo "style='".$inlineCss."'"; }; ?>>
			<option value=""><?php echo $first_option; ?></option>
			<?php foreach ($results as $result): ?>
				<option value="<?php echo $result['id']; ?>"><?php echo $result['name']; ?></option>
			<?php endforeach ?>
		</select>
		<?php
	}

	public function getSelectOfSubCategories( $settings = array() ) {
		// Setting the defaults
		if ( !empty($settings["id"]) ) { $id = $settings["id"]; } else { $id = false; }
		if ( !empty($settings["class"]) ) { $class = $settings["class"]; } else { $class = false; }
		if ( !empty($settings["multiple"]) ) { $multiple = $settings["multiple"]; } else { $multiple = false; }
		if ( !empty($settings["inlineCss"]) ) { $inlineCss = $settings["inlineCss"]; } else { $inlineCss = false; }
		if ( !empty($settings["name"]) ) { $name = $settings["name"]; } else { $name = "subCategory"; }
		if ( !empty($settings["order"]) ) { $order = $settings["order"]; } else { $order = "name ASC"; }
		if ( !empty($settings["mainCatID"]) ) { $mainCatID = $settings["mainCatID"]; } else { $mainCatID = 1; }
		if ( !empty($settings["first-option"]) ) { $first_option = $settings["first-option"]; } else { $first_option = "Select Sub Category"; }

		$this->query("SELECT * FROM categories_sub WHERE main_cat_id = :mainCatID ORDER BY ".$order );
		$this->bind(':mainCatID', $mainCatID );
		$subCategories = $this->fetchAll(); ?>
		<?php if ($subCategories): ?>
			<select name="<?php echo $name; ?>" <?php if ($id) { echo "id='".$id."'"; }; ?> <?php if ($class) { echo "class='".$class."'"; }; ?> <?php if ($multiple) { echo "multiple"; }; ?> <?php if ( $inlineCss ) { echo "style='".$inlineCss."'"; }; ?>>
				<option value="">-- <?php echo $first_option; ?> --</option>
				<?php foreach ($subCategories as $sCat): ?>
					<option value="<?php echo $sCat['id']; ?>"><?php echo $sCat['name']; ?></option>
				<?php endforeach ?>
			</select>
		<?php endif ?>
		<?php
	}


	public function getSelectOfAllCategories( $settings = array() ) { // name, order, first-option, id, class
		// Setting the defaults		
		if ( !empty($settings["id"]) ) { $id = $settings["id"]; } else { $id = false; }
		if ( !empty($settings["class"]) ) { $class = $settings["class"]; } else { $class = false; }
		if ( !empty($settings["name"]) ) { $name = $settings["name"]; } else { $name = "category"; }
		if ( !empty($settings["required"]) ) { $required = $settings["required"]; } else { $required = false; }
		if ( !empty($settings["multiple"]) ) { $multiple = $settings["multiple"]; } else { $multiple = false; }
		if ( !empty($settings["inlineCss"]) ) { $inlineCss = $settings["inlineCss"]; } else { $inlineCss = false; }
		if ( !empty($settings["price"]) ) { $price = $settings["price"]; } else { $price = false; }
		if ( !empty($settings["order"]) ) { $order = $settings["order"]; } else { $order = "name ASC"; }
		if ( !empty($settings["first-option"]) ) { $first_option = $settings["first-option"]; } else { $first_option = "-- Select Category --"; }

		$this->query("SELECT DISTINCT m.* FROM categories_main as m, categories_sub as s WHERE m.id = s.main_cat_id ORDER BY ".$order );
		$mainCategories = $this->fetchAll();
		?>
		<select name="<?php echo $name; ?>" <?php if ($id) { echo "id='".$id."'"; }; ?> <?php if ($class) { echo "class='".$class."'"; }; ?> <?php if ($required) { echo "required"; }; ?> <?php if ($multiple) { echo "multiple"; }; ?> <?php if ( $inlineCss ) { echo "style='".$inlineCss."'"; }; ?>>
			<option value=""><?php echo $first_option; ?></option>
			<?php if ($mainCategories): ?>
				<?php foreach ($mainCategories as $mCat): ?>
					<optgroup label="<?php echo Translate::string("categoryMain.".Product::slugify($mCat['name'])); ?>">
						<?php 
							$this->query("SELECT id, name FROM categories_sub WHERE main_cat_id = :mCatID ORDER BY ".$order );
							$this->bind(':mCatID', $mCat['id'] );
							$subCategories = $this->fetchAll();
						 ?>
						<?php foreach ($subCategories as $sCat): ?>
							<option value="<?php echo $sCat['id']; ?>" ><?php echo Translate::string("categorySub.".Product::slugify($sCat['name'])); // if ($price) { echo " (".$sCat['price_dkk']." DKK)"; } ?></option>
						<?php endforeach ?>
					</optgroup>
				<?php endforeach ?>
			<?php endif ?>
		</select>
		<?php
	}

	public function getAllCategories( $settings = array() ) {
		$this->query("SELECT DISTINCT m.name, m.id FROM categories_main as m, categories_sub as s WHERE m.id = s.main_cat_id ORDER BY m.name asc" );
		$mainCategories = $this->fetchAll();
		$returnArray = array();
		if ($mainCategories) {
			foreach ($mainCategories as $mCat) {
				$returnArray["categoryMain.".Product::slugify($mCat['name'])] = $mCat['name'];

				$this->query("SELECT id, name FROM categories_sub WHERE main_cat_id = :mCatID ORDER BY name asc" );
				$this->bind(':mCatID', $mCat['id'] );
				$subCategories = $this->fetchAll();
			 
				foreach ($subCategories as $sCat) {
					$returnArray["categorySub.".Product::slugify($sCat['name'])] = $sCat['name'];
				}
			}
		}
		return $returnArray;
	}	

	public function getSubCatPriceByID ( $ID = NULL ) {
		$this->query("SELECT price_dkk FROM categories_sub WHERE id = :ID");
		$this->bind(':ID', $ID );
		$price = $this->single();
		return $price["price_dkk"];
	}

	public function getProductPriceByID ( $ID = NULL ) {
		$this->query("SELECT price_dkk FROM products WHERE id = :ID");
		$this->bind(':ID', $ID );
		$price = $this->single();
		return $price["price_dkk"];
	}

	public function smartSearch($spec_name='', $attribute_slug='', $lang = "eng")
	{

		if ($lang != "eng") {
			$name_lang = "name_".strtolower($lang);
		} else {
			$name_lang = "name";
		}

		$limit = 10;
		$spec_name = $spec_name."%";

		$this->query("SELECT DISTINCT specs.id, specs.slug, specs.name, specs.name_nor 
						FROM specs, attributes, product_specs
						WHERE attributes.slug = :attribute_slug
						  AND attributes.id = product_specs.attribute_id
						  AND specs.id = product_specs.spec_id
						  AND (specs.name LIKE :spec_name OR specs.name_nor LIKE :spec_name)
						ORDER BY specs.count DESC, specs.name ASC LIMIT ".$limit);
		$this->bind(':attribute_slug', $attribute_slug );
		$this->bind(':spec_name', $spec_name );
		$this->bind(':spec_name', $spec_name );
		$rows = $this->fetchAll();

		echo "<ul>";
		if ($rows) {
			foreach ($rows as $row) { 
				
				if (!$row_name = $row[$name_lang]) {
					$row_name = $row["name"];
				}
				if (intval($row_name) <= 0) { // only display text not number values
					?><li data-id="<?php echo $row['id']; ?>" data-slug="<?php echo $row['slug']; ?>"><?php echo $row_name; ?></li><?php 
				}
			}
		} else {
			// echo "<li>No Result</li>";
		}

		if (count($rows) <= $limit) {
			$delta = $limit - count($rows);
			$this->query("SELECT DISTINCT id, slug, name, name_nor FROM specs
							WHERE name LIKE :spec_name OR name_nor LIKE :spec_name
							ORDER BY count DESC, name ASC LIMIT ".$delta);
			$this->bind(':spec_name', $spec_name );
			$this->bind(':spec_name', $spec_name );
			$rows2 = $this->fetchAll();
			if ($rows2) {
				echo "<li class='break'><hr></li>";
				foreach ($rows2 as $row2) { 
					if (!$row_name2 = $row2[$name_lang]) {
						$row_name2 = $row2["name"];
					}
					if (intval($row_name2) <= 0) { // only display text not number values
						?><li data-id="<?php echo $row2['id'] ?>" data-slug="<?php echo $row2['slug'] ?>"><?php echo $row_name2; ?></li><?php 
					}
				}
			}
		}

		echo "</ul>";

	}

	public function insertAdminLog( $employee, $message, $browser, $ip, $session_id, $status = "warning" )
	{
		try {
			$database = new Database();
			$database->query('INSERT INTO log_admin ( employee, message, date_time, ip_address, session_id, browser, status) 
											 VALUES (:employee,:message,:date_time,:ip_address,:session_id,:browser, status) ');
				
			$database->bind( ':employee', $employee );
			$database->bind( ':message', $message );
			$database->bind( ':date_time', date("Y-m-d H:i:s") );
			$database->bind( ':ip_address', $ip );
			$database->bind( ':session_id', $session_id );
			$database->bind( ':browser', $browser );
			$database->bind( ':status', $status );

			$insert = $database->execute();

			if (!$insert) { // inserting was succesfull
				throw new Exception("Error Processing Admin Log Insert", 1);
			}
		} catch (Exception $e) {
			return 'Error: ' .$e->getMessage();			
		}
		return true;
	}	

	public function smartSearchAttribute($attribute='', $lang = "eng")
	{

		if ($lang != "eng") {
			$row_name = "name_".strtolower($lang);
		} else {
			$row_name = "name";
		}

		$limit = 10;
		$attribute = $attribute."%";

		$this->query("SELECT DISTINCT * FROM attributes
						WHERE name LIKE :attribute or name_nor LIKE :attribute
						ORDER BY count DESC, name ASC LIMIT ".$limit);
		$this->bind(':attribute', $attribute );
		$this->bind(':attribute', $attribute );
		$rows = $this->fetchAll();

		if ($rows) {
			echo "<ul>";
			foreach ($rows as $row) { 
				?><li data-id="<?php echo $row['id'] ?>" data-slug="<?php echo $row['slug'] ?>"><?php echo $row_name; ?></li><?php 
			}
			echo "</ul>";
		}
	}

}


?>
/* 
*
* 	Creating the database for FinnPlus.No
*	-- Code By Rene Ollino
*
*/

DELIMITER ;

-- DROP DATABASE finnpluss_no_db;
-- CREATE DATABASE finnpluss_no_db;

CREATE TABLE locations (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	city_name varchar(100) NOT NULL UNIQUE,
	zip varchar(10) NOT NULL,
	country_code varchar(2) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE categories_main (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	name varchar(100) NOT NULL UNIQUE,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO categories_main (id, name)
VALUES 	('1', 'Transportation'),
		('2', 'Property'),
		('3', 'Electronics'),
		('4', 'Children\'s World'),
		('5', 'Home & Garden'),
		('6', 'Fashion & Clothes'),
		('7', 'Pets'),
		('8', 'Building Material'),
		('9', 'Collectibles'),
		
		('10', 'Sport'),
		('11', 'Entertainment'),
		('12', 'Jobs'),
		('13', 'Service'),
		('14', 'Free Stuff');

CREATE TABLE categories_sub (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	name varchar(100) NOT NULL,
	main_cat_id bigint(20) NOT NULL,
	price int(10) NOT NULL,
	currency_id bigint(20) NOT NULL DEFAULT 1,
	PRIMARY KEY (id),
	FOREIGN KEY (main_cat_id) REFERENCES categories_main(id) ON UPDATE CASCADE ON DELETE RESTRICT
	FOREIGN KEY (currency_id) REFERENCES currencies(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO categories_sub (main_cat_id, name, price)
VALUES 	(1, "Cars", 99),
		(1, "Trucks", 99),
		(1, "Bicycles", 99),
		(1, "Scooters", 99),
		(1, "Motorcycles", 99),
		(1, "ATVs", 99),
		(1, "Buggies", 99),
		(1, "Race Cars", 99),
		(1, "Mini Vans", 99),
		(1, "Buses", 99),
		(1, "Boats", 99),
		(1, "Yachts", 99),
		(1, "Small Airplanes", 99),
		(1, "Helicopters", 99),
		(2, "Property for sale", 99),
		(2, "Property for rent", 99),
		(2, "New homes", 99),
		(2, "Garage & Parking", 99),
		(2, "Land", 99),
		(2, "Abroad", 99),
		(2, "Commercial premises", 99),
		(2, "Looking for companion", 99),
		(3, "Phones & Smart phones", 99),
		(3, "Computers", 99),
		(3, "Radio & TV", 99),
		(3, "Music & Films", 99),
		(3, "Foto & Cameras", 99),
		(3, "Games", 99),
		(3, "Kitchen electronics", 99),
		(3, "Home electronics", 99),
		(3, "Outside electronics", 99),
		(3, "Others", 99),
		(4, "Children's clothing", 99),
		(4, "Toys", 99),
		(4, "Kid's products", 99),
		(4, "Children's furniture", 99),
		(4, "Children's transport", 99),
		(4, "Baby and Children's equipment", 99),
		(4, "Costumes", 99),
		(5, "Furniture", 99),
		(5, "Applied arts", 99),
		(5, "Kitchen", 99),
		(5, "Lighting", 99),
		(5, "Bathroom", 99),
		(5, "Heating", 99),
		(5, "Art", 99),
		(5, "Gardening tools", 99),
		(5, "Flowers and Plants", 99),
		(5, "Others", 99),
		(6, "Women's clothing", 99),
		(6, "Footwear", 99),
		(6, "Accessories", 99),
		(6, "Men's clothing", 99),
		(6, "Jewelry", 99),
		(6, "Cosmetics", 99),
		(6, "Special & Work clothing", 99),
		(6, "Costumes", 99),
		(6, "Other", 99),
		(7, "Horses", 99),
		(7, "Dogs", 99),
		(7, "Birds", 99),
		(7, "Cats", 99),
		(7, "Aquarium fish", 99),
		(7, "Reptiles", 99),
		(7, "Plants", 99),
		(7, "Rodents", 99),
		(7, "Rabbit", 99),
		(7, "Insects", 99),
		(7, "Accessories & Supplies", 99),
		(7, "Service for animals", 99),
		(7, "Other pets", 99),
		(8, "Glass and windows", 99),
		(8, "Doors", 99),
		(8, "Plumbers", 99),
		(8, "Electrical articles", 99),
		(8, "Ports", 99),
		(8, "Constructions tools & Equipment", 99),
		(8, "Other", 99),
		(9, "Other collectibles", 99),
		(9, "Porcelain glass & Cutlery", 99),
		(9, "Plates & Shapes", 99),
		(9, "Antiques", 99),
		(9, "Prescription envelopes", 99);

CREATE TABLE user_roles (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	role varchar(100) NOT NULL UNIQUE,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO user_roles (id, role)
VALUES 	('1', 'user'),
		('2', 'admin');

CREATE TABLE languages (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	eng_name varchar(100) NOT NULL UNIQUE,
	native_name varchar(100) NOT NULL UNIQUE,
	lang_code varchar(3) NOT NULL UNIQUE,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO languages (id, eng_name, native_name, lang_code)
VALUES 	('1', 'english', 'english', 'en'),
		('2', 'danish', 'dansk', 'dk');

CREATE TABLE users (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	name varchar(100) NOT NULL,
	role_id bigint(20) NOT NULL DEFAULT 1,
	email varchar(200) NOT NULL UNIQUE,
	phone int(20) NOT NULL,
	date_registered date NULL,
	birthday date NULL,
	lang_id bigint(20) NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (role_id) REFERENCES user_roles(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (lang_id) REFERENCES languages(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE companies (
	user_id bigint(20) NOT NULL auto_increment UNIQUE,
	company_name varchar(100) NOT NULL,
	company_number varchar(15) NOT NULL UNIQUE,
	company_address varchar(200) NOT NULL,
	company_zip varchar(10) NOT NULL,
	phone_2 int(20) NULL,
	PRIMARY KEY (user_id),
	FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Creating the Trigger to set the current date for the users table when a new user is created
DELIMITER //
DROP TRIGGER IF EXISTS setCurrDate_users_bi //
CREATE TRIGGER setCurrDate_users_bi 
BEFORE INSERT ON users
	FOR EACH ROW BEGIN
		IF (NEW.date_registered = NULL OR NEW.date_registered = '') THEN
			SET NEW.date_registered = CURRENT_DATE();
		END IF;
	END //
DELIMITER ;

CREATE TABLE currencies (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	currency varchar(100) NOT NULL UNIQUE,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO currencies (id, currency)
VALUES 	('1', 'EUR'),
		('2', 'DKK'),
		('3', 'NOK'),
		('4', 'USD');


CREATE TABLE payment_methods (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	method varchar(100) NOT NULL UNIQUE,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO payment_methods (id, method)
VALUES 	('1', 'cash'),
		('2', 'transfer');

CREATE TABLE product_status (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	status varchar(100) NOT NULL UNIQUE,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO product_status (id, status)
VALUES 	('1', 'draft'),
		('2', 'published'),
		('3', 'sold'),
		('4', 'deleted');

CREATE TABLE tags (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	tag varchar(100) NOT NULL UNIQUE,
	count int(10) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE specs (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	name varchar(100) NOT NULL UNIQUE,
	slug  varchar(100) NOT NULL,
	count int(10) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE attributes (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	sub_cat_id bigint(20) NOT NULL,
	attribute varchar(100) NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (sub_cat_id) REFERENCES categories_sub(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE product_contitions (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	product_contition varchar(100) NOT NULL UNIQUE,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO product_contitions (id, product_contition)
VALUES 	('1', 'used'),
		('2', 'new'),
		('3', 'mint'),
		('4', 'damaged');


CREATE TABLE products (
	id bigint(20) NOT NULL auto_increment UNIQUE,
	user_id bigint(20) NOT NULL,
	sub_cat_id bigint(20) NOT NULL,
	price int(10) NOT NULL,
	currency_id bigint(20) NOT NULL,
	condition_id bigint(20) NOT NULL,
	location_id bigint(20) NOT NULL,
	payment_method_id bigint(20) NOT NULL DEFAULT 1,
	description varchar(1000) NOT NULL,
	lang_id bigint(20) NOT NULL DEFAULT 1,
	top_add boolean NOT NULL DEFAULT 0,
	date_created date NOT NULL,
	date_last_edit date NULL,
	date_published date NULL,
	status_id bigint(20) NOT NULL DEFAULT 1,
	PRIMARY KEY (id),
	FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (sub_cat_id) REFERENCES categories_sub(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (currency_id) REFERENCES currencies(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (condition_id) REFERENCES product_contitions(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (lang_id) REFERENCES languages(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (status_id) REFERENCES product_status(id) ON UPDATE CASCADE ON DELETE RESTRICT
	FOREIGN KEY (location_id) REFERENCES locations(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE product_tags (
	product_id bigint(20) NOT NULL,
	tag_id bigint(20) NOT NULL,
	PRIMARY KEY (product_id, tag_id),
	FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (tag_id) REFERENCES tags(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE product_specs (
	product_id bigint(20) NOT NULL,
	attribute_id bigint(20) NOT NULL,
	spec_id bigint(20) NOT NULL,
	PRIMARY KEY (product_id, attribute_id, spec_id),
	FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (spec_id) REFERENCES specs(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE product_videos (
	product_id bigint(20) NOT NULL UNIQUE,
	video_embed varchar(1000) NOT NULL,
	adult boolean NOT NULL DEFAULT 0,
	PRIMARY KEY (product_id),
	FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE product_views (
	view_date date NOT NULL,
	ip_address varchar(100) NOT NULL,
	product_id bigint(20) NOT NULL,
	count int(10) NOT NULL,
	PRIMARY KEY (view_date, ip_address),
	FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE product_images (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	product_id bigint(20) NOT NULL,
	img_name varchar(100) NOT NULL,
	img_original longblob NOT NULL,
	img_large longblob NOT NULL,
	img_medium blob NOT NULL,
	img_thumb blob NOT NULL,
	img_type varchar(25) NOT NULL,
	date_uploaded date NOT NULL,
	adult boolean NOT NULL DEFAULT 0,
	PRIMARY KEY (id),
	FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE compare (
	user_id bigint(20) NOT NULL,
	sub_cat_id bigint(20) NOT NULL,
	product_id bigint(20) NOT NULL,
	PRIMARY KEY (user_id, product_id),
	FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (sub_cat_id) REFERENCES categories_sub(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE log_login (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	email varchar(200) NOT NULL,
	date_time datetime NOT NULL,
	ip_address varchar(64) NOT NULL,
	browser varchar(100) NOT NULL,
	status varchar(10) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE log_admin (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	employee bigint(20) NOT NULL,
	message varchar(200) NOT NULL,
	date_time datetime NOT NULL,
	ip_address varchar(45) NOT NULL,
	javascript tinyint(1) NOT NULL,
	browser varchar(100) NOT NULL,
	session_id char(32) NOT NULL,
	PRIMARY KEY (id), 
	FOREIGN KEY (employee) REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE log_error (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	user_id bigint(20) NOT NULL,
	date_time datetime NOT NULL,
	ip_address varchar(64) NOT NULL,
	browser varchar(100) NOT NULL,
	error_message varchar(3000) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE tokens (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	email varchar(200) NOT NULL,
	token char(64) NOT NULL,
	expiration_datetime datetime NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Creating the Trigger to set the current date for the products table when a new product is created
DELIMITER //
DROP TRIGGER IF EXISTS setCurrDate_products_bi //
CREATE TRIGGER setCurrDate_products_bi 
BEFORE INSERT ON products
	FOR EACH ROW BEGIN
		IF (NEW.date_created = NULL OR NEW.date_created = '') THEN
			SET NEW.date_created = CURRENT_DATE();
		END IF;
	END //

-- any time a product gets updated then change the date to
DELIMITER //
DROP TRIGGER IF EXISTS product_last_edited //
CREATE TRIGGER product_last_edited
AFTER UPDATE ON products
FOR EACH ROW BEGIN
UPDATE products set date_last_edit = CURRENT_DATE() WHERE id = OLD.id;
END //


DELIMITER ;

Create VIEW products_view AS 
SELECT p.id, p.user_id, sub.name AS sub_category,p.title,p.price,c.currency, 
	l.city_name, l.zip, l.country_code, pm.method AS payment_method, p.description, 
	lang.eng_name, lang.native_name, lang.lang_code, p.top_add, p.date_created, 
	p.date_last_edit, p.date_published, p.status_id 
FROM payment_methods pm, currencies c, products p, locations l, languages lang, categories_sub sub 
WHERE p.location_id = l.id 
AND p.currency_id = c.id 
AND p.payment_method_id = pm.id 
AND p.lang_id = lang.id 
AND p.sub_cat_id = sub.id;


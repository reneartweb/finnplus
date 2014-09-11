<?php 


	/**
	* @copyright Copyright (C) 2014 Rene Ollino & Adam Karcasony.
	* @license GPL
	*/

	// define('ALLOW_ACCESS', true); // allow access to this page
	defined('ALLOW_ACCESS') or die('Restricted access'); 	// Security to prevent direct access to php files.

	// Auto load the class when it is beeing created
	spl_autoload_register(function ($class) {
		require_once "../lib/classes/".$class.".class.php";
	});

	require_once "../lib/includes/session.php";

	// check if user is logged in or not
	$isLoggedIn = User::isLoggedIn();

	if ($isLoggedIn && isset($_SESSION['employee']) ) {
		$userID = $_SESSION["user_id"];
		// $_SESSION["employee"] = $userID; // user_id important for logging employee activity
		$user = new User();
		$user->getUser($userID);
	} else {
		User::unsetSession();
		header("Location: index.php");
	}


	$full_name 	= $_SERVER['PHP_SELF'];	
	$name_array = explode('/',$full_name);
	$page_name	= end($name_array);	
	$page_name = str_replace(".php", "", $page_name);

 ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Finnplus Admin section">
		<meta name="author" content="Rene Ollino">
		<!-- <link rel="shortcut icon" href="../../assets/ico/favicon.ico"> -->

		<title>Dashboard</title>

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles -->
		<link href="css/bootstrap-switch.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>

		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">FinnPlus Backend</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $user->name(); ?></a></a>
						  <ul class="dropdown-menu">
							<li><a href="#"><span class="glyphicon glyphicon-cog"></span> Change Profile</a></li>
							<li><a href="../lib/ajax/logout.php"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">Nav header</li>
							<li><a href="#">Separated link</a></li>
							<li><a href="#">One more separated link</a></li>
						  </ul>
						</li>
						<li><a href="#"><span class="glyphicon glyphicon-info-sign"></span> Help</a></li>
					</ul>
				<!-- <form class="navbar-form navbar-right">
						<input type="text" class="form-control" placeholder="Search...">
					</form> -->				
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-3 col-md-2 sidebar">
					<ul class="nav nav-sidebar">
						<li class="<?php echo ($page_name == 'dashboard') ? "active" : ""; ?>" ><a href="dashboard.php"><span class="glyphicon glyphicon-th"></span> Dashboard</a></li>
						<li class="<?php echo ($page_name == 'advertisments') ? "active" : ""; ?>" ><a href="advertisments.php"><span class="glyphicon glyphicon-picture"></span> Advertisments</a></li>
						
						<li class="dropdown <?php echo ($page_name == 'users' OR $page_name == 'employees') ? "active" : ""; ?>">
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> Users</a></a>
						  <ul class="dropdown-menu">
							<li><a href="users.php">All Users</a></li>
							<li><a href="companies.php">All Companies</a></li>
							<li><a href="employees.php">All Employees</a></li>
						  </ul>
						</li>

					</ul>
					<ul class="nav nav-sidebar">
						<li class="<?php echo ($page_name == 'categories') ? "active" : ""; ?>" ><a href="categories.php"><span class="glyphicon glyphicon-folder-open"></span> Categories</a></li>
						<li class="<?php echo ($page_name == 'attributes') ? "active" : ""; ?>" ><a href="attributes.php"><span class="glyphicon glyphicon-list-alt"></span> Attributes</a></li>
						<li class="<?php echo ($page_name == 'specifications') ? "active" : ""; ?>" ><a href="specifications.php"><span class="glyphicon glyphicon-list-alt"></span> Specifications</a></li>
					</ul>
					<ul class="nav nav-sidebar">
						<li class="<?php echo ($page_name == 'terms-of-service') ? "active" : ""; ?>" ><a href="terms-of-service.php"><span class="glyphicon glyphicon-file"></span> Terms of Service</a></li>
						<li class="<?php echo ($page_name == 'bad-words') ? "active" : ""; ?>" ><a href="bad-words.php"><span class="glyphicon glyphicon-thumbs-down"></span> Bad Words</a></li>
					</ul>
					<ul class="nav nav-sidebar">
						<li class="<?php echo ($page_name == 'translations') ? "active" : ""; ?>" ><a href="translations.php"><span class="glyphicon glyphicon-globe"></span> Translations</a></li>
						<!-- <li class="<?php echo ($page_name == 'employees') ? "active" : ""; ?>" ><a href="employees.php"><span class="glyphicon glyphicon-user"></span> Employees</a></li> -->
						<li class="disabled <?php echo ($page_name == 'reported-ads') ? "active" : ""; ?>" ><a href=""><span class="glyphicon glyphicon-warning-sign"></span> Reported Ad's</a></li>
						<li class="disabled <?php echo ($page_name == 'reported-images') ? "active" : ""; ?>" ><a href=""><span class="glyphicon glyphicon-warning-sign"></span> Reported Images</a></li>
						<li class="disabled <?php echo ($page_name == 'newsletters') ? "active" : ""; ?>" ><a href=""><span class="glyphicon glyphicon-envelope"></span> Newsletters</a></li>
					</ul>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
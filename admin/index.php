<?php 

  require_once "../lib/includes/session.php";
  require_once "../lib/includes/sanitize-all.php";

  if ( !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["javascript"])  ) {

    // Auto load the class when it is beeing created
    spl_autoload_register(function ($class) {
      require_once "../lib/classes/".$class.".class.php";
    });

    require_once "../lib/classes/Inspekt.php";

    if (!Inspekt::isEmail($_POST["email"])) {
      die("Please write a correct Email address");
    }

    $user = new User;
    $login = $user->checkCredentials($_POST["email"], $_POST["password"], $_POST["javascript"], $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], session_id() );
    if ($login && isset($_SESSION['employee'])) {
      header("Location: dashboard.php");
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- <link rel="shortcut icon" href="../../assets/ico/favicon.ico"> -->

    <title>Signin Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <form method="post" class="form-signin" role="form">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="checkbox" value="1" name="javascript" class="javascript-check hidden">
        <input name="email" type="email" class="form-control" placeholder="Email address" required autofocus>
        <input name="password" type="password" class="form-control" placeholder="Password" required>
        <label class="checkbox">
          <!-- <input type="checkbox" value="remember-me"> Remember me -->
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-switch.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/docs.min.js"></script>
    <script src="js/script.js"></script>
  </body>
</html>

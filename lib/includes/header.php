<?php


/**
 * @copyright Copyright (C) 2014 Rene Ollino & Adam Karcasony.
 * @license GPL
 */

// define('ALLOW_ACCESS', true); // allow access to this page
defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.

// Auto load the class when it is beeing created
spl_autoload_register(function ($class) { require_once "lib/classes/" . $class . ".class.php"; });

// add the custom class
require_once "lib/classes/Inspekt.php";
require_once "lib/includes/session.php";

// check if user is logged in or not
$isLoggedIn = User::isLoggedIn();

if ($isLoggedIn) {
    $userID = $_SESSION["user_id"];
    $user = new User();
    $user->getUser($userID);
} else {
    User::unsetSession();
}

// get current page name
$page_array = explode("/", $_SERVER["PHP_SELF"]);
$page_name = $page_array[count($page_array) - 1];

$current_ip_address = $_SERVER['REMOTE_ADDR'];

// get the language from the session, if it does not exist set the default as eng
if (isset($_SESSION["lang"])) {
    $lang = $_SESSION["lang"];
} else {
    $lang = "eng";
    $_SESSION["lang"] = $lang;
    setlocale(LC_ALL, "en_EN.UTF-8");
}

// if the lang is set then change the session
if (isset($_GET["lang"])) {
    $lang = $_GET["lang"];
    $_SESSION["lang"] = $lang;
}

// if lang is dk then change the date format to display danish month and day
if ($lang == "dk") {
    setlocale(LC_ALL, "da_DK.UTF-8");
}

if ($lang == "nor") {
    setlocale(LC_ALL, "no_NO.UTF-8");
}

function dateString($date, $format = "%d. %B %Y")
{
    return strftime($format, strtotime($date));
}

// Initiating a global variables
$GLOBALS['db'] = new Database();
$validate = new Validate();
// $user = new User("rene.ollino@gmail.com", "password");

// Get main objects with the order name desc and the free stuff category as last
$mainCategories = $db->getMainCategoriesArray("case when name = 'Free Stuff' then 2 else 1 end,name desc");

?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <title><?php echo $title; ?></title>
    <meta charset="utf-8">
    <meta name="author" content="Rene Ollino & Adam Karacsony">
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-type" content="text/html; charset=iso-8859-1">

    <?php // Touch icons for iOO devices ?>
    <link rel="apple-touch-icon" href="img/apple-touch-icon/darth-vader-60x60.png">                    <?php //touch-icon-iphone">?>
    <link rel="apple-touch-icon" sizes="76x76" href="img/apple-touch-icon/darth-vader-76x76.png">        <?php //touch-icon-ipad">?>
    <link rel="apple-touch-icon" sizes="120x120" href="img/apple-touch-icon/darth-vader-120x120.png">    <?php //touch-icon-iphone-retina">?>
    <link rel="apple-touch-icon" sizes="152x152" href="img/apple-touch-icon/darth-vader-152x152.png">    <?php //touch-icon-ipad-retina">?>

    <?php // Windows 8 & Windows Phones icons ?>
    <meta name="application-name" content="Darth Vader - Star Wars Characters" />
    <meta name="msapplication-TileColor" content="#000000" />
    <meta name="msapplication-square70x70logo" content="img/windows-icons/tiny.png" />
    <meta name="msapplication-square150x150logo" content="simg/windows-icons/quare.png" />
    <meta name="msapplication-wide310x150logo" content="img/windows-icons/wide.png" />
    <meta name="msapplication-square310x310logo" content="img/windows-icons/large.png" />

    <?php // Favicons ?>
    <link type="image/x-icon" rel="shortcut icon" href="favicon.ico"><?php // IE ?>
    <link type="image/x-icon" rel="icon" href="favicon.ico"><?php // other browsers ?>

    <?php // CSS ?>
    <link rel="stylesheet" media="all" type="text/css" href="lib/css/style.css">
    <link rel="stylesheet" media="all" type="text/css" href='http://fonts.googleapis.com/css?family=Lato:300,400'>

    <?php // Javascripts ?>
    <?php // <script src="lib/js/jq.1.9.1.min.js"></script> ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


</head>
<body>
<?php #<!-- Add loading cursor if javascript works and will be removed once document and javascript have loaded --> ?>
<script type="text/javascript">document.getElementsByTagName('body')[0].className += ' loading'</script>
<!--[if lte IE 8]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please
    <a href="http://browsehappy.com/?locale=en">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<section id="sticky-top">
    <div class="container">
        <a href="/">
            <img src="lib/images/elements/logo.svg" alt="logo of finnplus">
        </a>

        <form>
            <input type="text" name="top-search" placeholder="<?php echo Translate::string("navigation.search"); ?>">
            <button type="button" id="top-seach-btn"></button>
        </form>
        <div class="inline-block">
            <?php if ($isLoggedIn): ?>

                <div class="dropdown inline-block">
                    <label for="dropdown-2" class="dropdown-btn link"><?php echo $user->name(); ?> *</label><input type="checkbox" class="dropdown-checkbox" id="dropdown-2">
                    <ul class="hidden">
                        <li>
                            <a class="li my-ads-link" href="?my-ads#my-ads"><?php echo Translate::string("navigation.my_ads"); ?></a>
                        </li>
                        <li><a class="li" href="#"><?php echo Translate::string("navigation.my_settings"); ?></a></li>
                        <li>
                            <a class="li" href="lib/ajax/logout.php"><?php echo Translate::string("navigation.log_out"); ?></a>
                        </li>
                    </ul>
                </div>
                <a href="#" id="upgrade-btn"><?php echo Translate::string("navigation.upgrade"); ?></a>
                <?php //  Implement the upgrade button here, instead of logout?>
            <?php else: ?>
                <a href="?login#login" class="login-btn"><?php echo Translate::string("navigation.log_in"); ?></a>
                <a class="modal-btn" href="?register-modal"><?php echo Translate::string("navigation.register"); ?></a>
            <?php endif ?>
            <a href="#compare"><?php echo Translate::string("navigation.compare"); ?></a>
            <a href="#advert-intro" class="btn" id="create-ad-btn"><?php echo Translate::string("navigation.create_an_ad"); ?></a>
        </div>

    </div>
</section>

<section id="topnav">
    <nav class="container">
        <div class="left">
            <a href="#"><?php echo Translate::string("navigation.why_finnplus"); ?></a>
            <a href="#advert-intro"><?php echo Translate::string("navigation.how_to_create_an_ad"); ?></a>
            <a href="#"><?php echo Translate::string("navigation.contact"); ?></a>
        </div>
        <div id="finnplus-adbutton" class="right">
            <a href="#advert-intro" class="btn"><?php echo Translate::string("navigation.create_an_ad"); ?></a>
        </div>
        <div class="right" id="user-menus">
            <?php
            // Language Dropdown
            $settings = array(
                'id'       => "lang-dropdown",
                "class"    => "inline-block",
                "btn"      => Translate::string("navigation.language") . " ($lang)",
                "btnClass" => "link no-before",
            );
            $list = array('<a class="li" href="?lang=eng">ENG</a>', '<a class="li" href="?lang=nor">NOR</a>');

            FormElement::dropdown($settings, $list);

            ?>
            <?php if ($isLoggedIn): ?>

                <div class="dropdown inline-block">
                    <label for="dropdown-1" class="dropdown-btn link"><?php echo $user->name(); ?> *</label><input type="checkbox" class="dropdown-checkbox" id="dropdown-1">
                    <ul>
                        <li>
                            <a class="li my-ads-link" href="?my-ads#my-ads"><?php echo Translate::string("navigation.my_ads"); ?></a>
                        </li>
                        <li><a class="li" href="#"><?php echo Translate::string("navigation.my_settings"); ?></a></li>
                        <li>
                            <a class="li" href="lib/ajax/logout.php"><?php echo Translate::string("navigation.log_out"); ?></a>
                        </li>
                    </ul>
                </div>
                <a href="#" id="upgrade-btn"><?php echo Translate::string("navigation.upgrade"); ?></a>
                <?php //  Implement the upgrade button here, instead of logout?>
            <?php else: ?>
                <a href="?login" class="login-btn"><?php echo Translate::string("navigation.log_in"); ?></a>
                <a class="modal-btn" href="?register-modal"><?php echo Translate::string("navigation.register"); ?></a>
            <?php endif ?>
        </div>
    </nav>
</section><?php // #topnav ?>

<?php if ($isLoggedIn): ?>

    <section class="hidden">
        <div class="container">
            <!-- <p>Test number: <?php // echo Inspekt::getDigits('(765) fff 3.fa555-1234'); ?></p> -->
            <p>session timeout: <?php // echo $_SESSION["timeout"]; ?></p>

            <p>ip address: <?php // echo $current_ip_address; ?></p>
            <!-- <p>current time <?php // echo time(); ?></p> -->
            <p>session id <?php // echo session_id(); ?></p>
            <pre><?php // print_r($user); ?></pre>
        </div>
    </section>

<?php else: ?>

    <?php // Login ?>
    <section id="login" class="<?php echo (isset($_GET["login"])) ? "" : "hidden"; ?>">
        <div class="container">
            <form id="login-form" action="lib/ajax/login.php" method="post">
                <label for="login-email"><?php echo Translate::string("login.email"); ?></label>
                <input class="input" type="text" id="login-email" name="email" placeholder="<?php echo Translate::string("login.email"); ?>" autofocus="true">
                <label for="password"><?php echo Translate::string("login.password"); ?></label>
                <input class="input" type="password" id="password" name="password" placeholder="<?php echo Translate::string("login.password"); ?>">
                <input class="hidden javascript-check" type="checkbox" name="javascript" value="1">
                <input type="submit" value="<?php echo Translate::string("login.login-btn"); ?>">
            </form>
            <p id="forgot-password" class="hidden">
                <a class="modal-btn" href="?forgot-password-modal"><?php echo Translate::string("login.forgot_password"); ?></a>
            </p>
        </div>
    </section>

    <?php // Forgot Password Modal ?>
    <?php ob_start(); // Start recording the content for the modal ?>
    <form id="forgot-password-form" action="lib/ajax/forgot-password.php" method="post">
        <?php
        FormElement::input(array(
            'id'          => "forgot-password-email",
            'placeholder' => Translate::string("forgot_password.your_email"),
            'name'        => "email",
            'type'        => 'email',
            'label'       => Translate::string("forgot_password.label_your_email"),
            'required'    => true,
        ));
        ?>
        <button><?php echo Translate::string("forgot_password.send_email"); ?></button>
    </form>
    <p><?php echo Translate::string("forgot_password.footer_message"); ?></p>
    <?php
    $forgot_password_modal_content = ob_get_contents();
    ob_end_clean(); // end recording
    $forgot_password_modal_id = "forgot-password-modal";
    $forgot_password_modal_title = Translate::string("forgot_password.forgot_password_modal_title");
    $forgot_password_modal_footer = '<a href="#">' . Translate::string("forgot_password.already_a_user") . '</a>';
    // get the modal
    DocElement::modal($forgot_password_modal_id, $forgot_password_modal_title, $forgot_password_modal_content, $forgot_password_modal_footer);
    ?>

    <?php // Register modal ?>
    <?php ob_start(); // Start recording the content for the modal ?>
    <div class="register-type-block">
        <a href="register-form" class="btn btn-active">Private User</a>
        <a href="business-register-form" class="btn">Business User</a>
    </div>
    <form id="register-form" action="lib/ajax/register.php" method="post">
        <?php
        // version 1
        $settings = array(
            'id'          => "register-email",
            'placeholder' => Translate::string("register.placeholder_your_email"),
            'name'        => "email",
            'label'       => Translate::string("register.label_email"),
            'type'        => "email",
            'class'       => "one-liner",
            'required'    => true,
        );

        $formElement = new FormElement;
        $formElement->input($settings);

        // version 2
        FormElement::input(array(
            'id'          => "register-name",
            'placeholder' => Translate::string("register.placeholder_your_full_name"),
            'name'        => "name",
            'label'       => Translate::string("register.label_full_name"),
            'class'       => "one-liner",
            'required'    => true,
        ));

        FormElement::input(array(
            'id'          => "register-phone",
            'placeholder' => Translate::string("register.placeholder_your_phone_nr"),
            'name'        => "phone",
            'class'       => "one-liner",
            'label'       => Translate::string("register.label_phone_nr"),
            'required'    => true,
        ));

        FormElement::input(array(
            'id'          => "register-birthday",
            'placeholder' => Translate::string("register.placeholder_your_birthday"),
            'name'        => "birthday",
            'class'       => "one-liner",
            'label'       => Translate::string("register.label_birthday"),
            'required'    => false,
        ));

        FormElement::input(array(
            'id'          => "register-password",
            'placeholder' => Translate::string("register.placeholder_your_password"),
            'name'        => "password",
            'class'       => "one-liner",
            'label'       => Translate::string("register.label_password"),
            'type'        => "password",
            'required'    => true,
        ));

        FormElement::input(array(
            'id'          => "register-password-confirm",
            'placeholder' => Translate::string("register.placeholder_confirm_your_password"),
            'name'        => "confirm_password",
            'class'       => "one-liner",
            'label'       => Translate::string("register.label_confirm_password"),
            'type'        => "password",
            'required'    => true,
        ));
        ?>
        <button type="submit"><?php echo Translate::string("register.button"); ?></button>
    </form>

    <form id="business-register-form" class="hidden" action="lib/ajax/register-business.php" method="post">
        <?php

        FormElement::input(array(
            'id'          => "business-register-company-name",
            'placeholder' => Translate::string("register.business_placeholder_your_company_name"),
            'name'        => "company_name",
            'label'       => Translate::string("register.business_label_company_name"),
            'class'       => "one-liner",
            'required'    => true,
        ));

        FormElement::input(array(
            'id'          => "business-register-company-number",
            'placeholder' => Translate::string("register.business_placeholder_your_company_number"),
            'name'        => "company_number",
            'label'       => Translate::string("register.business_label_company_number"),
            'class'       => "one-liner",
            'required'    => true,
        ));

        FormElement::input(array(
            'id'          => "business-register-company-address",
            'placeholder' => Translate::string("register.business_placeholder_your_company_address"),
            'name'        => "company_address",
            'label'       => Translate::string("register.business_label_company_address"),
            'class'       => "one-liner",
            'required'    => true,
        ));        

        FormElement::input(array(
            'id'          => "business-register-company-zip",
            'placeholder' => Translate::string("register.business_placeholder_your_company_zip"),
            'name'        => "company_zip",
            'label'       => Translate::string("register.business_label_company_zip"),
            'class'       => "one-liner",
            'required'    => true,
        ));

        // version 2
        FormElement::input(array(
            'id'          => "business-register-name",
            'placeholder' => Translate::string("register.placeholder_your_full_name"),
            'name'        => "name",
            'label'       => Translate::string("register.label_full_name"),
            'class'       => "one-liner",
            'required'    => true,
        ));
        // version 1
        $settings = array(
            'id'          => "business-register-email",
            'placeholder' => Translate::string("register.placeholder_your_email"),
            'name'        => "email",
            'label'       => Translate::string("register.label_email"),
            'type'        => "email",
            'class'       => "one-liner",
            'required'    => true,
        );

        $formElement = new FormElement;
        $formElement->input($settings);

        FormElement::input(array(
            'id'          => "business-register-phone",
            'placeholder' => Translate::string("register.business_placeholder_1_phone_nr"),
            'name'        => "phone",
            'class'       => "one-liner",
            'label'       => Translate::string("register.business_label_1_phone_nr"),
            'required'    => true,
        ));

        FormElement::input(array(
            'id'          => "business-register-phone",
            'placeholder' => Translate::string("register.business_placeholder_2_phone_nr"),
            'name'        => "phone_2",
            'class'       => "one-liner",
            'label'       => Translate::string("register.business_label_2_phone_nr"),
            'required'    => false,
        ));       

        FormElement::input(array(
            'id'          => "business-register-birthday",
            'placeholder' => Translate::string("register.placeholder_your_birthday"),
            'name'        => "birthday",
            'class'       => "one-liner",
            'label'       => Translate::string("register.label_birthday"),
            'required'    => false,
        ));

        FormElement::input(array(
            'id'          => "business-register-password",
            'placeholder' => Translate::string("register.placeholder_your_password"),
            'name'        => "password",
            'class'       => "one-liner",
            'label'       => Translate::string("register.label_password"),
            'type'        => "password",
            'required'    => true,
        ));

        FormElement::input(array(
            'id'          => "business-register-password-confirm",
            'placeholder' => Translate::string("register.placeholder_confirm_your_password"),
            'name'        => "confirm_password",
            'class'       => "one-liner",
            'label'       => Translate::string("register.label_confirm_password"),
            'type'        => "password",
            'required'    => true,
        ));
        ?>
        <button type="submit"><?php echo Translate::string("register.button"); ?></button>
    </form>
    <?php
    $register_modal_content = ob_get_contents();
    ob_end_clean(); // end recording
    $register_modal_id = "register-modal";
    $register_modal_title = Translate::string("register.modal_title");
    $register_modal_footer = '<a href="?login">' . Translate::string("register.modal_footer") . '</a>';
    // get the modal
    DocElement::modal($register_modal_id, $register_modal_title, $register_modal_content, $register_modal_footer);
    ?>
<?php endif ?>

<section id="header">
    <div class="container">
        <a href="#" class="left" id="logo" style="position: relative;">
            <img src="lib/images/elements/logo.svg" alt="finnplus logo image">
            <span style="font-family: 'Lato', sans-serif; font-weight: 300; font-size: 1rem; position: absolute; left: 0; bottom: 2.2rem; color: #2b3990;" >New or Used</span>
            <span style="font-family: 'Lato', sans-serif; font-weight: 300; font-size: 1rem; position: absolute; right: 1.2rem; bottom: 2.2rem; color: #2b3990;" >Buy or Sell</span>
        </a>

        <div class="two-third right">
            <div id="search-container">
                <form id="search-form" action="main-search.php" method="GET" role="search">
                    <?php
                    $select_options = array(
                        "id"           => "search-cat-select",
                        "class"        => "btn",
                        "name"         => "category",
                        "first-option" => Translate::string("header.main_search_category_first_option"),
                        "required"     => false
                    );

                    $db->getSelectOfAllCategories($select_options);
                    ?>
                    <span id="search-cat-span" class="btn"><?php echo Translate::string("header.main_search_category_first_option"); ?></span>
                    <input type="text" name="search" placeholder="<?php echo Translate::string("header.main_search_placeholder"); ?>" required="required">
                    <button type="submit" id="search-btn"><?php echo Translate::string("header.main_search_button"); ?></button>
                </form>
            </div>
        </div>
    </div>
</section><?php // #header ?>

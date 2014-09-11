<?php 
	if (!$_SESSION) session_start();
	// define('ALLOW_ACCESS', true); // allow access to this page
	defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.
 
	// Reset Password Modal
	// only get the form when the token and email are valid
	if (!empty($_GET["reset-password"]) && !empty($_GET["email"]) ) {
		if (User::isTokenValid($_GET["email"],$_GET["reset-password"])) {
			ob_start(); // Start recording the content for the modal ?>
			<form id="reset-password-form" action="lib/ajax/reset-password.php" method="post" >
				<input type="hidden" name="token" required="required" value="<?php echo $_GET["reset-password"]; ?>">
				<input type="hidden" name="email" required="required" value="<?php echo $_GET["email"]; ?>">
				<input class="hidden javascript-check" type="checkbox" name="javascript" value="1">
				<?php 
					FormElement::input(array('id' => "new-reset-password", 'name' => "new-reset-password", 'label' => Translate::string("reset_password.new_passoword_label"), 'placeholder' => Translate::string("reset_password.new_passoword_placeholder"), 'type' => "password", 'required' => true ));
					FormElement::input(array('id' => "confirm-reset-password", 'name' => "confirm-reset-password", 'label' => Translate::string("reset_password.new_passoword_confirm_label"), 'placeholder' => Translate::string("reset_password.new_passoword_confirm_placeholder"), 'type' => "password", 'required' => true ));
				 ?>
				<button>Reset Password</button>
			</form>
			
			<?php
			$reset_password_modal_content = ob_get_contents();
			ob_end_clean(); // end recording
		} else {
			$reset_password_modal_content = "<p>".Translate::string("reset_password.expired_token")."</p>";
		}

		
		$reset_password_modal_id = "reset-password";
		$reset_password_modal_title = Translate::string("reset_password.modal_title");
		$reset_password_modal_footer = '<a href="#">'.Translate::string("reset_password.modal_footer").'</a>';
		// get the modal
		DocElement::modal($reset_password_modal_id, $reset_password_modal_title, $reset_password_modal_content, $reset_password_modal_footer);
	}

 ?>
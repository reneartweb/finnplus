<?php 
	if (!$_SESSION) session_start();
	// define('ALLOW_ACCESS', true); // allow access to this page
	defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.
 ?>

		<section id="footer">
			<footer class="container" role="contentinfo">
				<p><?php echo Translate::string("footer.legal_info"); ?></p>
				<p><?php echo Translate::string("footer.address"); ?></p>
				<img src="lib/images/elements/logo_footer.svg" alt="Finnplus footer logo square">
			</footer>
		</section><?php // #footer ?>

		<?php // Alert Modal 
			$title = Translate::string("alert_modal.title");
			$message = "";
			$footer = "";
			if (isset($_GET["title"])) {
				$title = $_GET["title"];
			}
			if (isset($_GET["alert"])) {
				$message = $_GET["alert"];
			}
			if (isset($_GET["footer"])) {
				$footer = $_GET["footer"];
			}
			$alert_modal_id = "alert";
			$alert_modal_content = "<p id='alert-paragraph'>".Inspekt::noTags($message)."</p>";
			// $alert_modal_content = '<img src="http://maps.googleapis.com/maps/api/staticmap?center=56.1138608,10.1577942&zoom=13&size=300x300&sensor=false">';
			$alert_modal_title = Inspekt::noTags($title);
			$alert_modal_footer = Inspekt::noTags($footer);
			// get the modal
			DocElement::modal($alert_modal_id, $alert_modal_title, $alert_modal_content, $alert_modal_footer);
		 ?>	

		<script src="lib/plugins/jquery.viewport.mini.js"></script>
		<script src="lib/plugins/jquery.event.drag-2.2/jquery.event.drag-2.2.js"></script>
		<script src="lib/js/javascript.js"></script>
		<script src="lib/js/form-elements.js"></script>
		<script src="lib/js/smart-search.js"></script>
		<script src="lib/js/jquery.easing.1.3.js"></script>
		<script src="lib/js/top-ads.js"></script>
		<script src="lib/js/expanded-view.js"></script>
		<script src="lib/js/sticky.js"></script>
		<script src="lib/js/upload-multiple-images.js"></script>
		<script src="lib/js/compare.js"></script>
	</body>
</html>
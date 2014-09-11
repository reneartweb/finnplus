<?php
$images = array();
$upload_dir = "../images/uploads/";

require_once "../includes/session.php";
spl_autoload_register(function ($class) { require_once "../classes/".$class.".class.php"; });

if ($_SERVER['CONTENT_LENGTH'] < 8380000) {
	if (isset($_FILES['upload_files']) && $_FILES['upload_files']['error'] != 0) {
		if (count($_FILES['upload_files']['tmp_name']) > 10) {
			$images = array("error"=>sprintf(Translate::string("upload_images_alert.max_img_allowed"), 10));
		} else {
			foreach($_FILES['upload_files']['tmp_name'] as $key=>$value) {
				$file = $_FILES['upload_files']['name'][$key];
				// $file = str_replace(" ", "+", $file);
				$file = htmlentities($file, ENT_COMPAT, 'UTF-8');

				$photo_exploded = explode(".", $file);
				$extension = end($photo_exploded);	

				$uuid = date("Ymd")."-".hash("sha256", $file.time()).".".$extension;
				if ($_SESSION["upload_img_count"] <= 10) {			

					if (preg_match('#image#',$_FILES['upload_files']['type'][$key])) {
						if (!move_uploaded_file($value, $upload_dir."temp/".$uuid)) {
							$images = array("error"=>"Server Error<br/>Reported to Admin");
						} else {
							if (!chmod($upload_dir."temp/".$uuid, 0777)) {
								$images = array("error"=>"Server Error2<br/>Reported to Admin");
							} else {
								$images[] = array('file_name' => $uuid );
								$_SESSION["upload_img_count"]++;
							}
						}
					} else {
						$images = array("error"=>Translate::string("upload_images_alert.only_images_allowed"));
					}
				}	
			}
		}
	}
} else {
	$images = array("error"=>sprintf(Translate::string("upload_images_alert.too_big_images"), 8));
}

?>
<html>
 <body>
  <script type="text/javascript">
  window.parent.Uploader.done('<?php echo json_encode($images); ?>');
  </script>
 </body>
</html>
<?php 
	if (!$_SESSION) session_start();
	if (isset($_POST["id"])) {
		require_once "../includes/sanitize-all.php";
		$cat_id = $_POST["id"];
		define('ALLOW_ACCESS', true); // allow access to this page when it is loaded with ajax

		// Auto load the class when it is beeing created
		spl_autoload_register(function ($class) {
			require_once "../classes/".$class.".class.php";
		});
	}
	if (isset($_GET["sub_cat_id"])) {
		require_once "lib/includes/sanitize-all.php";
		$sub_cat_id = $_GET["sub_cat_id"];
	}	
	defined('ALLOW_ACCESS') or die('Restricted access'); // Security to prevent direct access to php files.
	$db = new Database();
	$subCategories = $db->getSubCategoriesArray($cat_id, "CASE WHEN name = 'Other' THEN 2 ELSE 1 END,name ASC"); 
?>

<?php if ($subCategories): ?>
	<?php foreach ($subCategories as $subCat): ?>
	<?php 
		// $db->query('SELECT count(id) as total FROM products WHERE sub_cat_id = :subCatID AND status_id = 2');
		// $db->bind(':subCatID', $subCat['id']);
		// $count = $db->single();
	 ?>
		<a href="?cat_id=<?php echo $cat_id; ?>&amp;sub_cat_id=<?php echo $subCat['id']; ?>" data-id="<?php echo $subCat['id']; ?>" class="sub-category <?php echo ($subCat['id'] == $sub_cat_id) ? "sub-cat-active" : ""; ?>">
			<span><?php echo Translate::string("categorySub.".Product::slugify($subCat['name'])); ?><?php // echo $count["total"]; ?></span>
		</a>
	<?php endforeach ?>
<?php else: ?>
	<a href="?" data-id="0" class="sub-category"><span><?php echo Translate::string("categories.no_sub_categories"); ?></span></a>
<?php endif ?>
<script>
	$(document).ready(function() {
		$(".sub-category").click(function (e) {
			e.preventDefault();
			var sub_cat_title = $(this).text();
			var subCatID = $(this).attr("data-id");
			$("#sub_cat_id-input").val(subCatID);
			console.log(sessionStorage.root_url);
			var data = { sub_cat_id: subCatID };
			ajaxRequest = $.ajax({
			  type: "POST",
			  url: sessionStorage.root_url+"lib/includes/results-list.php",
			  data: data,
			  success: function (response) {
			  	categoryID = subCatID; // set the global categoryID
				top_ad_ajax(categoryID, 0, 1, "next"); // make the top ad container slide
				$("#results-list").html(response);
				$('html, body').animate({
					scrollTop: $("#top-ads").offset().top-$("#sticky-top").outerHeight()-5
				}, 500);
				$("#results-control").show(); // if user is viewing "my-ads", then the results-control is hidden
				var stateObj = data;
				// window.history.pushState(stateObj, sub_cat_title, sub_cat_title);
				// window.history.pushState(stateObj, sub_cat_title, "?sub_cat_id="+subCatID);
			  }
			});
		});		

		var currentTopAdPage = 0;
		var nextTopAdPage = 1;

		function top_ad_ajax (categoryID, currentTopAdPage, nextTopAdPage, direction) {
			ajaxRequest = $.ajax({
			  url: "lib/includes/top-ads.php",
			  data: {top_ad_page: nextTopAdPage, categoryID: categoryID},
			  success: function (response) {
			  	// get the content of "top-ads.php" and parse the html
			  	var html = $.parseHTML(response);
			  	// filter out the #top-ad-pages content
				html = $(html).find('#top-ad-pages').html();
				// append it to the container
		        $('#top-ad-pages').append(html);
		        $(".top-ad-page").removeClass("currentTopAdPage").removeClass("hidden").last().addClass("currentTopAdPage");
		        // make the switch animation
		        topAdSwitch(direction);
			  }
			});
		}

		function topAdSwitch(vector){
			var windowW = $(window).width();

			if (vector == "prev") {
				$("div[data-top-ad-page-id='"+currentTopAdPage+"']").animate({left: windowW, opacity: 0}, 500, "easeInCubic", function(){
					$("div[data-top-ad-page-id='"+currentTopAdPage+"']").hide();
					if(currentTopAdPage == 0){currentTopAdPage = 2;}
					else {currentTopAdPage--;}
					$("div[data-top-ad-page-id='"+currentTopAdPage+"']").css("left", "-"+windowW+"px");
					$("div[data-top-ad-page-id='"+currentTopAdPage+"']").show().delay(100).animate({left: 0, opacity: 1}, 500, "easeOutCubic");
				});

			}

			else if (vector == "next") {
				$("div[data-top-ad-page-id='"+currentTopAdPage+"']").animate({left: -windowW, opacity: 0}, 500, "easeInCubic", function(){
					$("div[data-top-ad-page-id='"+currentTopAdPage+"']").hide();
					if(currentTopAdPage == 2){currentTopAdPage = 0;}
					else {currentTopAdPage++;}
					$("div[data-top-ad-page-id='"+currentTopAdPage+"']").css("left", windowW+"px");
					$("div[data-top-ad-page-id='"+currentTopAdPage+"']").show().delay(100).animate({left: 0, opacity: 1}, 500, "easeOutCubic");
				});
			}
		}	
	});
</script>

$(document).ready(function() {

	// making the result_container full width of the screen
	var side_spacing = $("#results-list").offset().left;
	$(".result_container")
		.css("margin-left", "-"+side_spacing+"px")
		.css("padding-left", side_spacing+"px")
		.css("padding-right", side_spacing+"px");

	// var client = new XMLHttpRequest();
	// client.open("GET", "http://api.zippopotam.us/<?php echo $product_info['country_code']; ?>/<?php echo $product_info['zip']; ?>", true);
	// client.onreadystatechange = function() {
	// 	if(client.readyState == 4) {
	// 		var data = JSON.parse(client.response);
	// 		var longitude = data['places'][0]['longitude'];
	// 		var latitude = data['places'][0]['latitude'];

	// 		var img = new Image();
	// 		img.className = "GoogleMap";
	// 		img.src = "http://maps.googleapis.com/maps/api/staticmap?center=" + latitude + "," + longitude + "&zoom=11&size=165x165&sensor=true";
	// 		$("#GoogleMapThumbnail img").remove();
	// 		document.getElementById("GoogleMapThumbnail").appendChild(img);
	// 	};

		$(".img-gallery img").unbind().click(function () {
			var img = $(this).clone();
			$(".result_gallery img").remove();
			img[0].src = img[0].src.replace("thumbnail", "medium");
			$(img).appendTo(".result_gallery");
		});

	// };
	// client.send();

	// $("body").keydown(function(e) {
	// 	if(e.keyCode == 37) { // left
	// 		$(".prev-gallery-img").trigger("click");
	// 	} else if(e.keyCode == 39) { // right
	// 		$(".next-gallery-img").trigger("click");
	// 	}
	// });

	$(".prev-gallery-img").click(function (e) {
		e.preventDefault();
		var current_img = $(this).parent().parent().find("img").attr("data-id");
		var prev_img = $(this).parent().parent().next().next().find("img[data-id='"+current_img+"']").prev().clone();
		if (prev_img.length <= 0) {		
			var prev_img = $(this).parent().parent().next().next().find("img:last-child").clone();
		}
		$(".result_gallery img").remove();
		prev_img[0].src = prev_img[0].src.replace("thumbnail", "medium");
		$(prev_img).appendTo(".result_gallery");
	});

	$(".next-gallery-img").click(function (e) {
		e.preventDefault();
		var current_img = $(this).parent().parent().find("img").attr("data-id");
		var next_img = $(this).parent().parent().next().next().find("img[data-id='"+current_img+"']").next().clone();
		if (next_img.length <= 0) {		
			var next_img = $(this).parent().parent().next().next().find("img:first-child").clone();
		}
		$(".result_gallery img").remove();
		next_img[0].src = next_img[0].src.replace("thumbnail", "medium");
		$(next_img).appendTo(".result_gallery");
	});

	$(".google-maps").click(function () {
		var location = $(this).attr("data-location");
		alertModule('<iframe id="GoogleMapsIframe" src="https://www.google.com/maps/embed/v1/place?q='+location+'&key=AIzaSyBpxlnrOM1Ln0xsHbXSOBXK3rAeb4AT3Uw"></iframe>', "Google Maps");
	});

	$(".contact-seller").click(function (e) {
		e.preventDefault();
		var userID = $(this).attr("data-user-id");
		ajaxRequest = $.ajax({
		  url: "lib/ajax/contact-seller.php",
		  data: { user_id: userID },
		  success: function (response) {
			alertModule(response, alert_seller_info_title);

			// ajaxRequest = $.ajax({
			//   type: "GET" ,
			//   url: "http://graph.facebook.com/rene.ollino/picture?type=large",
			//   success: function (response) {
			//   	$("#fb-profile").html(response);
			//   }
			// });

		  }
		});
	});

	// ==============================================
	// ========= ADD TO COMPARE =====================
	// ==============================================

	$(".add-to-compare-btn").click(function (e) {
		e.preventDefault();
		var advert_id = $(this).attr("data-advert-id");
		$tis = $(this);
		var compare_count = $(".compare-item").length;
		$("#compare-title-count").text(compare_count);
		ajaxRequest = $.ajax({
		  url: "lib/ajax/add-to-compare.php",
		  data: { advert_id: advert_id },
		  success: function (response) {
			if (response == "already-set") {
				$tis.text("Already in Compare");
			} else {
				$tis.text("Added To Compare");
				$("#compare .table").append(response);
				var compareCount = $(".compare-item").length;
				$("#compare-title-count").text(compareCount);
			}
			$("#compare .remove-link").click(function(e){
				e.preventDefault();
				$(this).parent().parent().remove();
			});
		  }
		});
	});


});
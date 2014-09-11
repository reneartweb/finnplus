$(document).ready(function() {
	$(".prev-page, .next-page").click(function (e) {
		e.preventDefault();
		var oldVal = parseInt($("#page-input").val());
		var total = parseInt($("#total-pages").val());
		if ($(this).hasClass("prev-page")) {
			if (oldVal == 1 ) {
				$(this).css("background", "lightgrey").css("color", "black");
				return false;
			}
			$("#page-input").val(oldVal-1);
		} else {
			if (oldVal == total) {
				$(this).css("background", "lightgrey").css("color", "black");
				return false;
			}
			$("#page-input").val(oldVal+1);						
		};
		$("#results-control").submit();
		$('html, body').animate({
			scrollTop: $("#results-control").offset().top-40
		}, 500);
	});

	$("#pager select").change(function () {
		$("#page-input").val( $(this).val() );
		$("#results-control").submit();
	});

	$("#results-list").bind('drag', function (event) {
		$( this ).css( event.shiftKey ? { top: event.offsetY } : { left: event.offsetX });
	});

	$(".my-ads-link").click(function (e) {
		e.preventDefault();
		$("#results-control").hide();
		$("#results-list").load("lib/includes/my-ads.php", {my_ads : 1});
	});


	calculateLIsInRow('.gallery-view .ad'); 

	// =====================================================================================
	// ========= ADVERTISMENT CLICK EVENT ==================================================
	// =====================================================================================	

	$(".ad, .top-ad-item").click(function (e) {
		e.preventDefault();
		console.log("ad clicked");

		if (window.star_click !== 1) {
			click_ad($(this));
		};
	});


	// change layout without reloading the content
	$(".view-mode-input").change(function () {
		$(".ad-expanded").first().remove();
		$("#results-container script").remove();
		if ( $(this).val() == "gallery") {
			$("#results-list .ad").removeClass("row-break");
			$("#results-list").removeClass("list-view").addClass("gallery-view");
			$("#results-container").removeClass("list-view").addClass("gallery-view");
			calculateLIsInRow('#results-container .ad'); 
		} else {
			$("#results-list").removeClass("gallery-view").addClass("list-view");
			$("#results-container").removeClass("gallery-view").addClass("list-view");
			$("#results-list .ad").addClass("row-break");
		};
	});


	// ==============================================
	// ========= ADD TO COMPARE =====================
	// ==============================================

	$(".compare-icon").click(function (e) {
		e.preventDefault();
		window.star_click = 1;
		window.setTimeout(function(){
			window.star_click = 0;
		}, 100);
		var compare_count = $(".compare-item").length;
		$("#compare-title-count").text(compare_count);
		var advert_id = $(this).attr("data-advert-id");
		$tis = $(this);
		ajaxRequest = $.ajax({
		  url: "lib/ajax/add-to-compare.php",
		  data: { advert_id: advert_id },
		  success: function (response) {
			$tis.addClass("added-to-compare-star");
			if (response != "already-set") {
				$("#compare .table").append(response);
			}
			$("#compare .remove-link").click(function(e){
				e.preventDefault();
				$(this).parent().parent().remove();
			});
		  }
		});
	});

});
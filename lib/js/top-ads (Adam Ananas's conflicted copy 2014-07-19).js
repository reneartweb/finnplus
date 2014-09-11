// console.log("top-ads.js runs");
$(document).ready(function(){
	var currentTopAdPage = 0;
	var categoryID = 1;

	$(".top-ad-pager").click(top_ad_pager_click);

	function top_ad_pager_click () {
		// e.preventDefault();
		if (window.topAdExpanded == 1) {
			closeExpanded();
			$('html, body').animate({
				scrollTop: $( $("#top-ads") ).offset().top-100
			}, 500);
		};
		$Tis = $(this);
		var currentTopAdPage = $Tis.attr("data-current-page");

		var direction = $Tis.attr("data-direction");

		if (currentTopAdPage == 0 && direction == "prev") {
			top_ad_no_left();
		}

		else if (currentTopAdPage >= 0) {
			// change the data-page attribute of the buttons
			$(".top-ad-pager").each(function () {
				var page_nr = $(this).attr("data-page");
				if (direction == "prev") {
					page_nr = parseInt(currentTopAdPage) - 1;
				} else {
					page_nr = parseInt(currentTopAdPage) + 1;
				}
				$(".top-ad-pager").attr("data-current-page", page_nr); //currentTopAdPage
			});

			if (direction == "prev") {
				var nextTopAdPage = parseInt(currentTopAdPage) - 1;
			} else {
				var nextTopAdPage = parseInt(currentTopAdPage) + 1;
			}
			  	console.log("nextTopAdPage1 : "+nextTopAdPage);

			top_ad_ajax(categoryID, currentTopAdPage, nextTopAdPage, direction);
		};
	}

	function top_ad_ajax(categoryID, currentTopAdPage, nextTopAdPage, direction) {
		ajaxRequest = $.ajax({
		  url: "lib/includes/top-ads.php",
		  data: {top_ad_page: nextTopAdPage, categoryID: categoryID},
		  success: function (response) {
		  	console.log("nextTopAdPage : "+nextTopAdPage);
		  	// get the content of "top-ads.php" and parse the html
		  	var html = $.parseHTML(response);
		  	// filter out the #top-ad-pages content
			html = $(html).find('#top-ad-pages').html();
			// append it to the container
	        $('#top-ad-pages').append(html);
	        // make the switch animation
	        topAdSwitch(direction);
		  }
		});
	}
	

	// $("#top-ad-control-left").click(function(){
	// 	console.log("left clicked");
	// 	topAdSwitch(-1);
	// });
	// $("#top-ad-control-right").click(function(){
	// 	console.log("right clicked");
	// 	topAdSwitch(1);
	// });

	function topAdSwitch(vector){
		var windowW = $(window).width();
		if (vector == "prev") {
			$("div[data-top-ad-page-id='"+currentTopAdPage+"']").animate({left: windowW, opacity: 0}, 500, "easeInCubic", function(){
				$("div[data-top-ad-page-id='"+currentTopAdPage+"']").addClass("hidden").hide();
				$(".top-ad-page").removeClass("currentTopAdPage");
				if(currentTopAdPage == 0){currentTopAdPage = 2;}
				else {currentTopAdPage--;}
				$("div[data-top-ad-page-id='"+currentTopAdPage+"']").css("left", "-"+windowW+"px");
				$("div[data-top-ad-page-id='"+currentTopAdPage+"']").removeClass("hidden").addClass("currentTopAdPage").show().delay(100).animate({left: 0, opacity: 1}, 500, "easeOutCubic");
			});

		}

		else if (vector == "next") {
			$("div[data-top-ad-page-id='"+currentTopAdPage+"']").animate({left: -windowW, opacity: 0}, 500, "easeInCubic", function(){
				$("div[data-top-ad-page-id='"+currentTopAdPage+"']").addClass("hidden").hide();
				$(".top-ad-page").removeClass("currentTopAdPage");
				if(currentTopAdPage == 2){currentTopAdPage = 0;}
				else {currentTopAdPage++;}
				$("div[data-top-ad-page-id='"+currentTopAdPage+"']").css("left", windowW+"px");
				$("div[data-top-ad-page-id='"+currentTopAdPage+"']").removeClass("hidden").addClass("currentTopAdPage").show().delay(100).animate({left: 0, opacity: 1}, 500, "easeOutCubic");
			});
		}
	}


	function top_ad_no_left() {
		console.log("top ad no left");
		var windowW = $(window).width();
		var offset = windowW / 10;
		$(".currentTopAdPage").animate({left: offset}, 100, "easeOutCubic", function(){
			$(".currentTopAdPage").animate({left: 0}, 200, "easeInCubic");
		})
	}



	setTimeout(function() {
		window.onmousemove = handleMouseMove;

		var topAd = $("#top-ads");
		var topAdLeft =topAd.offset.left;
		console.log("pupa:" + topAdLeft);

	    function handleMouseMove(event) {
	        event = event || window.event; // IE-ism
	        // event.clientX and event.clientY contain the mouse position
	    }

		if (window.topAdExpanded !== 1) {
			setInterval(function(){
				console.log("slide it!");
			}, 5000)
		};
	}, 300);
});
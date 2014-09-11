// =====================================================================================
// ========= COOKIES =============================================================
// =====================================================================================

var writeCookie = function (name,value,days) {
    var date, expires;
    if (days) {
        date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires=" + date.toGMTString();
            }else{
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

var readCookie = function (name) {
    var i, c, ca, nameEQ = name + "=";
    ca = document.cookie.split(';');
    for(i=0;i < ca.length;i++) {
        c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1,c.length);
        }
        if (c.indexOf(nameEQ) == 0) {
            return c.substring(nameEQ.length,c.length);
        }
    }
    return '';
}

// Load when document is ready
$(document).ready(function() {

	$(".register-type-block .btn").click(function (e) {
		e.preventDefault();
		$(".register-type-block .btn").removeClass("btn-active");
		$(this).addClass("btn-active");
		$("#register-form, #business-register-form").addClass("hidden");
		var target = $(this).attr("href");
		$("#"+target).removeClass("hidden");

	});

	var root_url = window.location.protocol + "//" + window.location.host + window.location.pathname;
	sessionStorage.root_url = root_url;

	$('a[href^="#"]').click(function(){
		var anchor = $(this).attr("href");
		$('html, body').animate({
			scrollTop: $( $.attr(this, 'href') ).offset().top - $("#sticky-top").outerHeight()
		}, 500);
		return false;
	});

	var ads_in_viewport_string = readCookie("ads_in_viewport");
	if (ads_in_viewport_string == "") {
		ads_in_viewport_string = "[]";
	}
	window.ads_in_viewport = JSON.parse(ads_in_viewport_string);

	$(window).scroll( function () {
		$('.ad:in-viewport').each(function () {
		    // The element is visible, do something
		    var product_id = $(this).attr("data-id");
		    if (ads_in_viewport.indexOf(product_id.toString()) == -1) {
				ads_in_viewport.push(product_id);
				writeCookie("ads_in_viewport", JSON.stringify(ads_in_viewport), 1);
				$.ajax({
				  type: "POST",
				  timeout: 20000, // 10 sec
				  url: "lib/ajax/activity_statistics.php",
				  data: { product_id: product_id, action: "view"},
				  success: function (response) {
					if (response == "success") {
						console.log(product_id);
					} else {
						console.log("error in .ad:in-viewport");
					};
				  }
				});	
		    } else {
		    	console.log("already in array");
		    }
		});

	});
	// =====================================================================================
	// ========= AJAX SETTINGS =============================================================
	// =====================================================================================

	var ajaxRequest = null;
	$.ajaxSetup({
		type: "POST",
		timeout: 20000, // 10 sec
		dataType: "html",
		beforeSend : function() { 
			$("body").addClass("loading"); // Show a loading cursor whenever an Ajax request starts (and none is already active).
			if (ajaxRequest != null) ajaxRequest.abort(); // check if a request is current then abort it before making a new request
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (errorThrown == "") {
				alertModule(error_adblock);
			} else if (errorThrown == "timeout") {
				alertModule(error_slow_internet, error_slow_internet_title)
			} else {
				console.log("Status: " + textStatus);
				console.log("Error: " + errorThrown);
			};
		},
		complete: function () {
			$("body").removeClass("loading"); // Remove the loading cursor after all the Ajax requests have stopped.
		}
	});


	// =====================================================================================
	// ========= LOGIN, REGISTER & FORGOT PASSWORD =========================================
	// =====================================================================================

	$(".login-btn").click(function (e) {
		e.preventDefault();
		$("#login").toggleClass("hidden");
	});

	$("#login-form").submit(function (e) {
		e.preventDefault();
		ajaxRequest = $.ajax({
		  url: "lib/ajax/login.php",
		  data: $(this).serialize(),
		  success: function (response) {
			if (response == "success") {
				location.reload();
			} else {
				alertModule(response);
				$("#forgot-password").removeClass("hidden");
			};
		  }
		});
	});

	$("#register-form, #business-register-form").submit(function (e) {
		e.preventDefault();
		ajaxRequest = $.ajax({
		  url: $(this).attr("action"),
		  data: $(this).serialize(),
		  success: function (response) {
			if (response == "success") {
				location.reload();
			} else {
				alertModule(response);
			};
		  }
		});
	});

	$("#search-form").submit(function (e) {
		e.preventDefault();
		ajaxRequest = $.ajax({
		  type: "GET",
		  url: "lib/ajax/main-search.php",
		  data: $(this).serialize(),
		  success: function (response) {
			$("#results-container").html(response);
			var n = $("#results-container .ad").length;
			var scroll_position = $("#results").offset().top -  $("#sticky-top").outerHeight();
			$(".ad").click(function (e) {
				e.preventDefault();
				click_ad($(this));
			} );
			$('html, body').animate({
				scrollTop: scroll_position
			}, 1000);
			$(".results-list-title").text(results_list_title.replace("%s", n));
		  }
		});
	});	

	$("#forgot-password-form").submit( function (e) {
		e.preventDefault();
		ajaxRequest = $.ajax({
		  url: "lib/ajax/forgot-password.php",
		  data: $(this).serialize(),
		  success: function (response) {
			alertModule(response);
			$(".modal").addClass("hidden");
		  }
		});
	})

	$("#reset-password-form").submit( function (e) {
		e.preventDefault();
		ajaxRequest = $.ajax({
		  url: "lib/ajax/reset-password.php",
		  data: $(this).serialize(),
		  success: function (response) {
			if (response == "success") {
				window.location = "?alert="+alert_reset_password_success.replace(" ", "+");
			} else {
				alertModule(response);
			};
		  }
		});
	})

	// =====================================================================================
	// ========= CATEGORIE EVENTS ==========================================================
	// =====================================================================================

	// $(".category").click(function (e) {
	// 	e.preventDefault();
	// 	$(".category").hide();
	// 	$(this).show();
	// 	var cat_title = $(this).text();
	// 	$(".category-back-btn").show();
	// 	var id = $(this).children("a").attr("data-id");
	// 	$("#sub-category-container").html(loading_img);
	// 	ajaxRequest = $.ajax({
	// 	  url: "lib/ajax/getSubCategories.php",
	// 	  data: { id: id },
	// 	  success: function (response) {
	// 	  	console.log("ajax response");
	// 		$("#sub-category-container").html(response);
	// 		resizeSubCatContainer(".sub-category");
	// 		var stateObj = { id: id };
	// 		// window.history.pushState(stateObj, cat_title, cat_title);
	// 		window.history.pushState(stateObj, cat_title, '?cat_id='+id);
	// 	  }
	// 	});
	// 	$("#sub-category-container").css("display", "inline-block").css("width", "");
	// });

	// $(".category-back-btn").unbind().click(function (e) {
	// 	e.preventDefault();
	// 	$(".category").show();
	// 	$(" #sub-category-container, .category-back-btn ").hide();
	// 	window.history.pushState(' ', " ", '?');
	// });


	// =====================================================================================
	// ========= RESULT CONTROLLER EVENTS ==================================================
	// =====================================================================================

	$("#results-control").submit(function (e) {
		e.preventDefault();
		$Tis = $(this);
		// replace the content with the ajax-loader gif
		$("#results-list").slideUp(function () {
			ajaxRequest = $.ajax({
			  url: "lib/includes/results-list.php",
			  data: $Tis.serialize(),
			  success: function (response) {
				$("#results-list").html(response).slideDown();
				// $("#pager select").change(function () {
				// 	$("#results-control").append("<input name='page' value='"+$(this).val()+"' type='hidden'>").submit();
				// });
			  }
			});
		});
	});

	// get new content when results-control selects change
	$("#results-control select").change(function () {
		$("#results-control").submit();
	});

	// =====================================================================================
	// ========= CREATE advertisement =======================================================
	// =====================================================================================

	$("#create-main-cat li").click(function () {
		// get the initially clicked input
		var cat_id = $(this).children("input").val();
		// uncheck whatever was checked before by the php $_GET
		$("#create-main-cat input").prop("checked", false);
		// make the currently clicked input to checked
		$(this).children("input").prop("checked", true);
		// perform the ajax request
		ajaxRequest = $.ajax({
		  url: "lib/ajax/getSubCategoriesAsRadioList.php",
		  data: { cat_id: cat_id },
		  success: function (response) {
			$("#create-sub-cat ul").html(response);
			$("#create-sub-cat li").click(sub_cat_click);
		  }
		});
	});

	$("#create-sub-cat li").click(sub_cat_click);
	function sub_cat_click() {
		// get the initially clicked input
		var subCatID = $(this).children("input").val();
		// uncheck whatever was checked before by the php $_GET
		$("#create-sub-cat input").prop("checked", false);
		// make the currently clicked input to checked
		$(this).children("input").prop("checked", true);
		// perform the ajax request
		ajaxRequest = $.ajax({
		  url: "lib/includes/create-advert/form-steps/step-1b.php",
		  data: { subCatID: subCatID },
		  success: function (response) {
			$("#step-1b").html(response).removeClass("hidden");
			$("#step-1a-checkbox").prop("checked", true);
			$("#advert-img-form").removeClass("hidden");
			$("#back-to-step1a").click(function () {
				$("#advert-img-form").addClass("hidden");
				$("#step-1b").addClass("hidden");
			});
		  }
		});
	}

	// =====================================================================================
	// ========= MODALS ====================================================================
	// =====================================================================================

	// show  modal when modal-btn is clicked
	$(".modal-btn").click(function (e) {
		e.preventDefault();
		var href = $(this).attr("href");
		var modal = href.replace("?", "#");
		if ( $(modal).length < 1 ) {
			alertModule("Modal does not exist.");
		} else {
			$(modal).removeClass("hidden");
		} ;
	});	

	// close the modal if the click happens outside the modal wrap or on the close modal btn
	$(".close-modal-btn, .modal").click(function (e) {
		e.preventDefault();
		$(".modal").addClass("hidden");
	});

	// dont close the modal if the click happens insite the .wrap
	$('.modal .wrap').click(function(event){
		event.stopPropagation();
	});

	// =====================================================================================
	// ========= OTHER JAVASCRIPT EVENTS ===================================================
	// =====================================================================================

	$(".preventDefault").click(function (e) {
		e.preventDefault();
	});

	// check if javascript is enabled or not for keeping log of the login
	if ($(".javascript-check").length > 0) {
		$(".javascript-check").each(function () {
			$(this).prop("checked", true);
		});
	};

	// set a global loading image
	var loading_img = '<div class="loading-gif-container"><span class="align-helper"></span><img src="lib/images/elements/ajax-loader.gif" alt="loading" class="loading-gif"></div>';
	// load it to the page so it will be cashed
	$("body").append(loading_img);
	// hide it
	$(".loading-gif-container").hide();

	// calculating the list items in a row
	calculateLIsInRow('.gallery-view .ad'); 

	// if the window is beeing resized then recalculate list items in row
	$( window ).resize(function() { 
		calculateLIsInRow('.gallery-view .ad');
	});

	// hide the dropdown list when the cursor leaves it
	$(".dropdown ul").mouseleave(function (argument) {
		$(this).prev().prop("checked", false);
	});

	// animate compare btn
	$("#compare-btn").click(function (e) {
		e.preventDefault();
		$(this).toggleClass("btn-active");
		if ($("#compare-checkbox").is(":checked")) {
			$("#compare-checkbox").prop("checked", false);
		} else {
			$("#compare-checkbox").prop("checked", true);			
		}
	});


	$("#search-cat-select")
		.width($("#search-cat-span").width())
		.on("change", function () {
			var val = $(this).val();
			var selected = $("#search-cat-select option[value='"+val+"']").text()
			$("#search-cat-span").text(selected);
			$("#search-cat-select").width($("#search-cat-span").width());
		})
		.hover(function () {
			$("#search-cat-span").addClass("btn-hover");
		}, function () {
			$("#search-cat-span").removeClass("btn-hover");
		});

	$(".remove-from-compare-btn").click(function (e) {
		e.preventDefault();
		var advert_id = $(this).attr("data-id");
		var compareCount = $(".compare-item").length;
		$("#compare-title-count").text(compareCount);
		ajaxRequest = $.ajax({
		  url: "lib/ajax/remove-from-compare.php",
		  data: { advert_id: advert_id },
		  success: function (response) {
			$(this).closest(".compare-item").fadeOut("slow", function () {
			});
		  }
		});
	});
	// =====================================================================================
	// ========= GOOGLE MAPS IMG BASED ON COMPUTER GEOGRAPHIC LOCATION =====================
	// =====================================================================================

	// var img = new Image();
	// var mapContainer = "step-1b-column-1";

	// if(navigator.geolocation) {
	// 	$("body").addClass("loading");
	// 	console.log("start-loading geolocation");
	// 	navigator.geolocation.getCurrentPosition(function success (position) {
	// 		$("body").removeClass("loading");
	// 		console.log(position);
	// 		var latitude = position.coords.latitude;
	// 		var longitude = position.coords.longitude;

	// 		img.id = "mapImage";
	// 		img.alt = "Google Maps view of current location";
	// 		img.src = "http://maps.googleapis.com/maps/api/staticmap?center=" + latitude + "," + longitude + "&zoom=13&size=367x300&sensor=true";
	// 		document.getElementById(mapContainer).appendChild(img);

	// 	}, function error (argument) {
	// 		$("body").removeClass("loading");
	// 	});
	// }


	 // function processAjaxData (response, urlPath){
	 //     document.getElementById("content").innerHTML = response.html;
	 //     document.title = response.pageTitle;
	 //     window.history.pushState({"html":response.html,"pageTitle":response.pageTitle},"", urlPath);
	 // }

	window.onpopstate = function(e){
		if(e.state){
			document.getElementsByTagName("body").innerHTML = e.state.html;
			document.title = e.state.pageTitle;
		}
	};
	
	$("body").removeClass("loading"); // remove loading cursor when body and javascript have loaded
}); // end of document ready function


// try {
// 	$(".dd-images").msDropDown();
// } catch(e) {
// 	alertModule(e.message);
// }


// ********************
// **** Functions *****
// ********************

function calculateLIsInRow(element) {
	var lisInRow = 0;
	var break_class = "row-break";
	// count the first row
	$(element).each(function() {
		if( $(this).prev().length > 0 && $(this).position().top != $(this).prev().position().top) 
			return false;

		lisInRow++;
	});
	// number each element
	var item_number = 0;
	$(element).each(function () {
		item_number++;
		$(this).attr("data-item-number", item_number);
	});
	// if there is any row-break then remove the class
	$(element).removeClass(break_class);
	// get the number of total elements
	var total_elements = $(element).length;
	// each time the element data-item-number is the same as the number of items in a row, then add a class row-break
	var element_in_row = 0;
	while (total_elements > element_in_row) {
		element_in_row += lisInRow;
		$(element+"[data-item-number='"+element_in_row+"']").addClass(break_class);
	}
	// add a row-break to the last element just in case the row is not full
	$(element+"[data-item-number='"+total_elements+"']").addClass(break_class);
	// clean up the html and remove the data-item-number attribute
	$(element).removeAttr("data-item-number");
};

function alertModule (content, title, footer) {
   title = typeof title !== 'undefined' ? title : "Alert";
   footer = typeof footer !== 'undefined' ? footer : "";

	$("#alert .modal-content p").html(content);
	$("#alert header h2").text(title);
	$("#alert footer").text(footer);
	$("#alert").removeClass("hidden");
}

function resizeSubCatContainer(element) {
	
	var rows = 1;
	// calculate how many rows does it have
	$(element).each(function() {
		if ( $(this).prev().length > 0 && $(this).position().top != $(this).prev().position().top) 
			rows++;
	});

	if (rows <= 3) {
		// If there are less or equal to 3 rows, make the width of the sub-category-container smaller by 5 px
		var width = $("#sub-category-container").width();
		$("#sub-category-container").width(width-5);
		resizeSubCatContainer(element);
	} else {
		// Else if there are more than 3 rows, then make the widht back wider 5px
		var width = $("#sub-category-container").width();
		$("#sub-category-container").width(width+5);		
	}
};

function click_ad(Tis) {
	console.log("ad expanding");
	// if it has been clicked
	if (Tis.hasClass("active")) { 
		// remove all clicked
		$(".ad").removeClass("active");
		// remove element from the DOM
		$("#results-list .ad-expanded").fadeOut("slow", function () {
			$(this).remove();
		});
	} else { // if the element it is not clicked
		
		if (window.topAdExpanded == 1) {
			closeExpanded();
		};

		window.topAdExpanded = 1;
		$(".ad").removeClass("active"); // remove prevoiusly set clicked elements
		Tis.addClass("active"); // add active class to this element
		var product_id = Tis.attr("data-id");
		ajaxRequest = $.ajax({
		  type: "POST",
		  url: "lib/ajax/advertisement-expanded.php",
		  data: { product_id: product_id },
		  success: function (response) {
			var content = response;

			if ( $(".ad-expanded").length > 0) { // if expanded element exists
				// replace the content without animation
				$("#results-list .ad-expanded").remove();
				if ( Tis.hasClass("row-break") ) { Tis.after(content); } 
				else { Tis.nextAll(".row-break").first().after(content); }
				// var scroll_position = Tis.nextAll(".ad-expanded").first().offset().top - ( $(window).height() - $(".result_container").height() ) / 2;
				var scroll_position = Tis.nextAll(".ad-expanded").first().offset().top -  $("#sticky-top").outerHeight();
				$('html, body').animate({
					scrollTop: scroll_position
				}, 1000);

			} else { // if expanded element does not exist
				// prepare the content
				if ( Tis.hasClass("row-break") ) { Tis.after(content); } 
				else { Tis.nextAll(".row-break").first().after(content); }
				// fade in the content and after the fade in is done
				Tis.nextAll(".ad-expanded").first().hide().fadeIn("slow", function () {
					// animate scrolling so that the ad-expanded is in the center
					// var scroll_position = Tis.nextAll(".ad-expanded").first().offset().top - ( $(window).height() - $(".result_container").height() ) / 2;
					
					if ( Tis.parent().hasClass("list-view") ) {
						var scroll_position = Tis.offset().top - $("#sticky-top").outerHeight() - 7;
					} else {
						var scroll_position = Tis.nextAll(".ad-expanded").first().offset().top - $("#sticky-top").outerHeight();						
					}
					$('html, body').animate({
						scrollTop: scroll_position
					}, 1000);
				});
			}

			// if ( Tis.parent().hasClass("list-view") ) {
			// 	$(".arrow").hide();
			// } else {
				// placing the arrow the the center of the selected element
				var box_widht = Tis.width();
				var box_pos = Tis.offset().left-$("#results-list").offset().left;
				var arrow_widht = $(".arrow").outerWidth();
				var arrow_pos = box_pos+(box_widht-arrow_widht)/2;

				// making the arrow movement smooth and animated			
				var $arrow_style_element = $(".arrow-style");
				if ($arrow_style_element.length > 0) { $arrow_style_element.remove(); }; // check if the style element for the arrow exists, if so then remove it and replace with the following code
				$("head").append('<style class="arrow-style">.arrow {left:'+arrow_pos+'px;}</style>');
			// } end of else statement

			// making the result_container full width of the screen
			var side_spacing = $("#results-list").offset().left;
			$(".result_container")
				.css("margin-left", "-"+side_spacing+"px")
				.css("padding-left", side_spacing+"px")
				.css("padding-right", side_spacing+"px");

			// if top ad was expanded, make vertical space for the expanded view
			if (Tis.hasClass("top-ad-item")) {
				topAdExpandSpace();
			};
		
			// if the expanded view is called at the top ads, measure the expanded view's height and increase
			// the space by that amount so the elements would not overlap.
			function topAdExpandSpace(e) {
				// if (e.hasClass("top-ad-item") && window.topAdExpanded !== 1) {
					setTimeout(function() {
						var topAdExpandSpace = $(".ad-expanded").height();
						var topAdH = $("#top-ad-pages").height();
						var newTopHeight = topAdExpandSpace+topAdH;
						$("#top-ad-pages").css("height", newTopHeight+"px");
						// window.topAdExpanded = 1;
					}, 300);
				// };
			}
			
			// close the ad-expanded if the visitor clicks on the close-ad-btn or html
			$(".close-ad-btn").click(function (e) {
				e.preventDefault();
				closeExpanded();
			});

			// don't close the ad-expanded if the click happens inside the .ad
			// $('.ad-expanded, .ad').click(function(event){
			// 	event.stopPropagation();
			// });
		  } // end of success: function	
		});

	}; // end of else statement

	// callBack();

}// end of advertisement click function

function closeExpanded() {
	window.topAdExpanded = 0;
	window.TopAdCanSlide = 1;
	var ExpandedHeight = $(".ad-expanded").height();
	// if expanded was open at the top ads, scroll up the window until the top ads
	if ($(".ad").parents('#top-ads').length) {
		$('html, body').animate({
			scrollTop: $( $("#top-ads") ).offset().top
		}, 500);
	}
	$(".ad").removeClass("active");
	$(".top-ad-item").removeClass("active");
	$('#top-ad-pages').delay(200).css("height", "auto");
	$(".ad-expanded").fadeOut("slow", function () {
		$(this).remove();
		console.log("expanded closed");
	});
}
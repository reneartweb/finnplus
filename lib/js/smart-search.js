// NEEDS TO BE IN A SEPPERATE FILE, DO NOT COMBINE WITH OTHER SCRIPTS

$(document).ready(function() { // Load when document is ready
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
	
	// clone previous element, make visible and insert before the element it was cloned from
	// $("#add-more-details-btn").prev().clone().removeClass("hidden").insertBefore($("#add-more-details-btn").prev());

	$(".add-more-details-btn").click(function (e) {
		e.preventDefault();
		// clone previous element, make visible and insert before the element it was cloned from
		$(this).prev().clone().removeClass("hidden").insertBefore($(this).prev());

		$(".smart-search input:last-child").unbind().keyup(smart_search).parent().mouseleave(function () {
			$(".smart-search-container").remove();
		});
		$(".smart-search-attribute").unbind().keyup(smart_search_attribute).parent().mouseleave(function () {
			$(".smart-search-container").remove();
		});

		$(".remove-more-derails-input").unbind().click(function (e) {
			e.preventDefault();
			$(this).parent().remove();
		});
		$(".add-more-checkbox-btn").unbind().click(add_more_checkbox);
		$(".remove-checkbox-input").unbind().click(remove_checkbox_input);
	});

	$(".add-more-checkbox-btn").unbind().click(add_more_checkbox);
	function add_more_checkbox (e) {
		e.preventDefault();
		$clone = $(this).prev().clone().removeClass("hidden");
		$clone.find("input").attr("required", true);
		($clone).insertBefore($(this).prev());

		var i = 0;
		$(".detail-checkbox-label").each(function () {
			$(this).attr("name", "detail-checkbox-label["+i+"]");
			$(this).parent().next().find(".add-more-checkbox-input").each(function () {
				$(this).attr("name", "detail-checkbox-value["+i+"][]");
			})
			i++;
		});

		$(".remove-checkbox-input").unbind().click(remove_checkbox_input);
	}

	function remove_checkbox_input (e) {
		e.preventDefault();
		var count = $(this).parent().parent().find("input").length;
		// if it is the last visible btn clicked, then remove the whole container
		console.log(count);
		if ( count <= 4 ) {
			$(this).parent().parent().parent().remove();
		} else { // remove just the one
			$(this).parent().remove();
		}
	}
	
	$(".remove-more-derails-input").unbind().click(function (e) {
		e.preventDefault();
		$(this).parent().remove();
	});


	$(".smart-search input:last-child").unbind().keyup(smart_search);

	function smart_search() {
		$tis = $(this);
		var SPEC = $(this).val();
		var ATTRIBUTE = $(this).attr("name");
		var left = $tis.offset().left-$("#create-form").offset().left;
		var width = $tis.outerWidth();
		var height = $tis.outerHeight();
		$(".smart-search-container").remove();

		ajaxRequest = $.ajax({
		  url: "lib/ajax/smart-search.php",
		  data: { spec: SPEC, attribute: ATTRIBUTE },
		  success: function (response) {
			$tis.parent().append("<div class='smart-search-container' style='margin-top:"+height+"px;left:"+left+"px;width:"+width+"px;'>"+response+"</div>");
			$(".smart-search-container li").click(function () {
				$tis.val($(this).text());
				$(".smart-search-container").remove();
			});
			$(".smart-search-container").parent().parent().mouseleave(function () {
				$(".smart-search-container").remove();
			});
		  }
		});

	};


	$(".smart-search-attribute").unbind().keyup(smart_search_attribute);
	//.parent().mouseleave(function () {
	//	$(".smart-search-container").remove();
	//});
	function smart_search_attribute() {
		$tis = $(this);
		var ATTRIBUTE = $(this).val();
		var left = $tis.offset().left;
		var width = $tis.outerWidth();
		var height = $tis.outerHeight();
		$(".smart-search-container").remove();

		ajaxRequest = $.ajax({
		  url: "lib/ajax/smart-search-attribute.php",
		  data: { attribute: ATTRIBUTE },
		  success: function (response) {
			$tis.parent().append("<div class='smart-search-container' style='margin-top:"+height+"px;left:"+left+"px;width:"+width+"px;'>"+response+"</div>");
			$(".smart-search-container li").click(function () {
				$tis.val($(this).text());
				$(".smart-search-container").remove();
			});
		  }
		});
	};

	$("#create-form").unbind().submit(function (e) {
		e.preventDefault();
		ajaxRequest = $.ajax({
		  url: $(this).attr('action'),
		  data: $(this).serialize(),
		  success: function (response) {
			console.log("Response: "+response);
			console.log("parseInt: "+parseInt(response));
			// if response is not an integer
			if ( isNaN(parseInt(response)) ) {
				alertModule(response);
			} else {
				// set the response as the product id
				var ProductID = response;
				// animate the form up
				$("#step-1").slideUp("slow", function () {
				    // load the preview to its container
					$("#step-2 #preview-container").load("lib/ajax/advertisement-expanded.php", { product_id : ProductID } , function () {
						// make the preview visible with a slideDown animation
						$("#step-2").slideDown("slow", function () {
							// after slideDown animation is done, scroll to step 2
						    $('html, body').animate({
						        scrollTop: $("#step-2").offset().top-$("#sticky-top").outerHeight()
						    }, 2000);
						    // move the frame
							$("#create-step-frame").css("left","39.5%");
							// make next step active
							$(".step-number").removeClass("step-active");
							$("#create-validate .step-number").addClass("step-active");

						});
					});
				});				
			}			
		  }
		});
	});

	// disable autocompleet to all the inputs that have the smart-search function
	$(".smart-search input").each(function () {
		$(this).attr("autocomplete", "off");
	});

	console.log("smart-search loaded");	
});
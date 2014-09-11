$(document).ready(function(){

	$(".dropdown ul label").click(function(){
		var newItem = $(this).html();
		$(this).closest("ul").siblings(".dropdown-btn").html(newItem);
		$(".dropdown-checkbox").prop('checked', false); // hide the ul by unchecking the checkbox (css manipulation)
	});

	// Number inputs
	$(".number-input-wrap .number-input-up").click(function(){
		var field = $(this).parent().siblings("input");
		var  oldVal = field.val();
		oldVal = oldVal.replace(" ", ""); // remove spaces if there are any
		if (oldVal == undefined || oldVal.length < 1) {
			oldVal = 0;
		}
		else {
			oldVal = oldVal.replace(/[^\d.]/g, "");
		};
		var newVal = parseInt(oldVal);
		newVal++;
		field.val(newVal);
	});
	$(".number-input-wrap .number-input-down").click(function(){
		var field = $(this).parent().siblings("input");
		var  oldVal = field.val();
		oldVal = oldVal.replace(" ", ""); // remove spaces if there are any
		if (oldVal == undefined || oldVal.length < 1) {
			oldVal = 0;
		}
		else {
			oldVal = oldVal.replace(/[^\d.]/g, "");
		};
		var newVal = parseInt(oldVal);
		if (newVal > 0 ) { // decrase the value only if the it is bigger than 0
			newVal--;
			field.val(newVal);			
		};
	});

	$(".form-element-wrap").each(function () {
		var count =  $(this).find(".element-wrap").length;
		var limit = 4;
		if (count > limit) {
			var i = 0;
			$(this).find(".element-wrap").each(function () {
				i++;
				if (i > limit) {
					$(this).addClass("hide-form-element");
				};
			});
			$(".hide-form-element").hide();
			$(this).find(".add-more-checkbox-btn").hide();
			$(this).append("<a class='btn view-all-form-elements-btn' >"+form_view_all_elements+" ("+count+")</a>");
		};
	});

	$(".view-all-form-elements-btn").click(function (e) {
		e.preventDefault();
		$(this).parent().find(".hide-form-element").show();
		$(this).parent().find(".add-more-checkbox-btn").show();
		$(this).hide();
	});

	$(".view-all-details-btn").click(function (e) {
		e.preventDefault();
		$(this).parent().find(".hide-input-element").css("display", "inline-block");
		$(this).hide();
	});

});

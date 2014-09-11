$(document).ready(function(){
	// table sorting
	$('<span class="glyphicon glyphicon-sort hidden"></span>').appendTo(".table th");
	$('.table th:first-child .glyphicon').removeClass("hidden");
	$('.table th').click(function () {
		$('.table th .glyphicon').addClass("hidden");
		$(this).find(".glyphicon").removeClass("hidden");
	});
	// initiate tablesorter plugin
	$(".table").tablesorter();

	// category tabs
	$('#categoryTabs a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	})

	$(".bootstrapSwitch").bootstrapSwitch({
        state: true,
        size: 'mini',
        animate: true,
        disabled: false,
        readonly: false,
        onColor: "success",
        offColor: "danger",
        onText: "YES",
        offText: "NO",
        labelText: "&nbsp;"
      });

	// $(".alert").fadeOut(5000);

	$("#categoryTabs li:first-child").addClass("active");
	$("#categoryTabs + .tab-content .tab-pane:first-child").addClass("active");

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
				alert("If you have AdBlock extension, please consider pausing it to use our website or better yet, don't run it on this domain.");
			} else if (errorThrown == "timeout") {
				alert("Seems like the Internet connection was a bit slow. Could not finish the request. Please try again.", "Timeout")
			} else {
				console.log("Status: " + textStatus);
				console.log("Error: " + errorThrown);
			};
		},
		complete: function () {
			$("body").removeClass("loading"); // Remove the loading cursor after all the Ajax requests have stopped.
		}
	});

	// fix the bug (clicking on the label does not submit)
	$(".change-login-permission-form label").unbind().click(function (e) {
		e.preventDefault();
		$(this).parent().find(".bootstrapSwitch").bootstrapSwitch('toggleState');
	});

	// submit when form (switch element) gets clicked
	$(".change-login-permission-form").click(function () {
		$(this).submit();
	});

	$(".change-login-permission-form").submit(function (e) {
		e.preventDefault();
		ajaxRequest = $.ajax({
		  url: $(this).attr("action"),
		  data: $(this).serialize(),
		  success: function (response) {
			if (response == "success") {
				console.log("Login Permission change success");
			} else {
				alert(response);
				location.reload();
			}
		  }
		});
	});

	 $(".change-subcategory-form, .add-sub-category-form, .change-specification-form, .change-attributes-form").submit(function (e) {
		e.preventDefault();
		ajaxRequest = $.ajax({
		  url: $(this).attr("action"),
		  data: $(this).serialize(),
		  success: function (response) {
			if (response == "success") {
				alert("Saved");
			} else {
				alert(response);
			}
			location.reload();
		  }
		});
	 });

	 $(".translate-form").submit(function (e) {
		e.preventDefault();
		ajaxRequest = $.ajax({
		  url: $(this).attr("action"),
		  data: $(this).serialize(),
		  success: function (response) {
			alert(response);
		  }
		});
	 });	 

	 $(".merge-spec").click(function (e) {
		e.preventDefault();
		var id = $(this).attr("data-id");
		var merge_id=prompt("Enter the Spec ID to merge "+id+" with.");

		if (merge_id==null) return false;

  		ajaxRequest = $.ajax({
		  url: "ajax/merge-specifications.php",
		  data: {id: id, merge_id: merge_id},
		  success: function (response) {
		  	console.log("response");
			if (response != "success") {
				alert(response);
			}
			location.reload();
		  }
		});
	 });

	 $(".merge-attr").click(function (e) {
		e.preventDefault();
		var id = $(this).attr("data-id");
		var merge_id=prompt("Enter the Attr ID to merge "+id+" with.");

		if (merge_id==null) return false;

  		ajaxRequest = $.ajax({
		  url: "ajax/merge-attributes.php",
		  data: {id: id, merge_id: merge_id},
		  success: function (response) {
		  	console.log("response");
			if (response != "success") {
				alert(response);
			}
			location.reload();
		  }
		});
	 });

	$(".delete-user-btn").click(function (e) {
		e.preventDefault();
		var r=confirm("Are you sure you want to delete this user? All user advertisements will be deleted as well. This can not be undone.");
		if (r==false) return false;

		ajaxRequest = $.ajax({
		  url: "ajax/delete-user.php",
		  data: {userID : $(this).attr("data-user-id")},
		  success: function (response) {
			if (response != "success") {
				alert(response);
			}
			location.reload();
		  }
		});
	});

	$(".delete-translation").click(function (e) {
		e.preventDefault();
		var $Tis = $(this);
		var r=confirm("Are you sure you want to delete this translation? This can not be undone.");
		if (r==false) return false;

		ajaxRequest = $.ajax({
		  url: "ajax/delete-translation.php",
		  data: $Tis.attr("data-name") + "=" + $Tis.attr("data-value"),
		  success: function (response) {
			alert(response);
			$Tis.closest("tr").remove();
		  }
		});
	});

	$(".delete-spec").click(function (e) {
		e.preventDefault();
		var count = $(this).attr("data-count");
		var r=confirm("Are you sure you want to delete this spec? There are "+count+" advertisements with this spec. In all of these ad's this spec will be removed. This can not be undone.");
		if (r==false) return false;
		ajaxRequest = $.ajax({
		  url: "ajax/delete-specification.php",
		  data: {id : $(this).attr("data-id")},
		  success: function (response) {
			if (response != "success") {
				alert(response);
			}
			location.reload();
		  }
		});
	});

	$(".delete-attr").click(function (e) {
		e.preventDefault();
		var count = $(this).attr("data-count");
		var r=confirm("Are you sure you want to delete this attribute? There are "+count+" advertisements with this attribute. In all of these ad's this attribute will be removed. This can not be undone.");
		if (r==false) return false;
		ajaxRequest = $.ajax({
		  url: "ajax/delete-attribute.php",
		  data: {id : $(this).attr("data-id")},
		  success: function (response) {
			if (response != "success") {
				alert(response);
			}
			location.reload();
		  }
		});
	});

	// check if javascript is enabled or not for keeping log of the login
	if ($(".javascript-check").length > 0) {
		$(".javascript-check").each(function () {
			$(this).prop("checked", true);
		});
	};

	$(".delete-sub-cat").click(function (e) {
		var r=confirm("Are you sure?");
		if (r==true) {
			e.preventDefault();
			ajaxRequest = $.ajax({
			  url: "ajax/delete-subcategory.php",
			  data: {id : $(this).attr("data-id"), name : $(this).attr("data-name")},
			  success: function (response) {
				if (response == "success") {
					alert("Deleted");
				} else {
					alert(response);
				}
				location.reload();
			  }
			});
		}
	});

	$(".trash-advertisement").click(function (e) {
		if (confirm("Are you sure you want to trash this advertisement?")) {
			e.preventDefault();
			var advert_id = $(this).attr("data-advert-id");
			ajaxRequest = $.ajax({
			  url: "../lib/ajax/trash-advertisement.php",
			  data: { advert_id : advert_id },
			  success: function (response) {
				alert(response);
				location.reload();
			  }
			});
		}
	});

});
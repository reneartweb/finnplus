	console.log("upload-multiple-images");
	var images_count = 0;

	var Uploader = (function () {

		$('#upload_files').on('change', function () {
			images_count = $("#uploaded_images img").length;
			if (images_count < 10) {
				$("body").addClass('loading');
				$('#upload_files').parent('form').submit();
			} else {
				alertModule(sprintf(save_advertisement_max_imagex, 10));
			}
		});

		function fnUpload () {
			$('#upload_files').trigger('click');
		}

		function fnDone (data) {
			$('#upload_files').val("");
			$("body").removeClass('loading');
			var data = JSON.parse(data);
			if (typeof (data['error']) != "undefined") {
				alertModule(data['error']);
				$('#upload_files').val("");
				$("body").removeClass('loading');
				return;
			}
			var divs = [];
			var fields = [];
			var counter = images_count;
			console.log(images_count);
			var alert = null;
			for (i in data) {
				if (counter < 10) {
					fields.push("<input type='hidden' name='advert-img[]' value='"+data[i]["file_name"]+"'>");
					divs.push("<div class='uploaded-img-thumbnail'><img title='Remove' alt='"+data[i]["file_name"]+"' src='lib/images/uploads/temp/"+data[i]["file_name"]+"'></div>");
					counter++;
				} else {
					alert = "Only 10 images are allowed";
				}
			}
			if (alert!=null) {alert(alert);}

			$(fields.join("")).appendTo('#create-form');
			$(divs.join("")).appendTo('#uploaded_images');
			$("#step-1b-column-1").css("padding-bottom", "160px");

			$(".uploaded-img-thumbnail").unbind().click(function () {
				var name = $(this).find("img").attr("alt");
				$("input[value='"+name+"']").remove();
				
				$(this).fadeOut(function () {
					$(this).remove();
				});
			});
		}

		return {
			upload: fnUpload,
			done: fnDone
		}

	}());
console.log("sticky.js runs");
$(document).ready(function(){

	// grow and srink the search bar
	$("#sticky-top input").focus(function(){
		$("#sticky-top form").animate({width: "50%"}, 600);
	});
	$("#sticky-top input").focusout(function(){
		$("#sticky-top form").animate({width: "6rem"}, 1000);
	});

 	 navigator.sayswho = (function(){
			var N= navigator.appName, ua= navigator.userAgent, tem;
			var M= ua.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i);
			if(M && (tem= ua.match(/version\/([\.\d]+)/i))!= null) M[2]= tem[1];
			M= M? [M[1], M[2]]: [N, navigator.appVersion,'-?'];
			return M;
		})();
 
	var browser = navigator.sayswho[0];

	if (browser == "Chrome") {
		var bodyelem = $("body");
	} else if (browser == "Firefox") {
		var bodyelem = $("body, html");
	} else if (browser == "Safari") {
		var bodyelem = $(document);
	} else {
		var bodyelem = $(window);
	}

	// detect scroll position
	scrolling = function() {
		var scrollpos = bodyelem.scrollTop();
		var topbarHeight = $("#header").height();
		if (scrollpos > topbarHeight) {
			$("#sticky-top").show();
		} else {
			$("#sticky-top").hide();
		}
	}

	scrolling();

	if (window.addEventListener) {
		window.addEventListener('scroll', scrolling, false);
	} else if (window.attachEvent) {
		window.attachEvent('onscroll', scrolling);
	}

});
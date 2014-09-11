$(document).ready(function(){
	console.log("temp-x.js runs");

	 	window.TopAdCanSlide = 1;
	    $("#top-ad-pages, .top-ad-pager").hover(function(){
	    	console.log("noslide!");
	    	window.TopAdCanSlide = 0;
	    }, function(){
	    	console.log("slide");
	    	window.TopAdCanSlide = 1;
	    });

		if (window.topAdExpanded !== 1) {
			setInterval(function(){
				if (window.TopAdCanSlide === 1) {
					// slide
					top_ad_pager_click();
				};
			}, 1000)
		};
	// }, 300);
});
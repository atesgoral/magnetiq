$(document).ready(function () {
	// Add panel backgrounds
	$(".panels a").each(function (i, a) {
		a.style.backgroundImage =
			"url(project_images/" + /#(.+)/.exec(a.href)[1] + ".jpg)";
	});

	// Clone navigation and add top borders
	var nav = $(".nav");
	
	$(".section").slice(1).addClass("top_border").each(function (i, sect) {
		var h2 = $("h2", sect);
		
		h2.before(
			nav.clone().attr("id", h2.attr("id"))
		).attr("id", "");
	});
});

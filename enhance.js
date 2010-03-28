$(document).ready(function () {
	// Add panel backgrounds
	$(".panels a").each(function () {
		var tokens = /panel_(.+)/.exec(this.className);
		
		if (tokens) {
			this.style.backgroundImage =
				"url(panel_thumbs/" + tokens[1] + ".jpg)";
		}
	});

	// Clone navigation and add top borders
	var nav = $(".nav");
	
	$(".section").slice(1).addClass("top_border").each(function () {
		var h2 = $("h2", this);
		
		h2.before(
			nav.clone().attr("id", h2.attr("id"))
		).attr("id", "");
	});
});

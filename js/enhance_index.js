$(function () {
	// Add panel backgrounds
	$(".panels li").each(function () {
		var a = $("a", this).get(0);
		var tokens = /p_(.+)/.exec(a.className);
		
		if (tokens) {
			a.style.backgroundImage =
				"url(http://s.magnetiq.com/panel_thumbs/" + tokens[1] + ".png)";
			$(this).addClass("panel");
			$(a).addClass("panel");
		}
	});

	// Clone navigation and add top borders
	var nav = $("#about");
	
	$(".section").slice(1).addClass("top_border").each(function () {
		var h2 = $("h2", this);
		
		h2.before(
			nav.clone().attr("id", h2.attr("id"))
		).attr("id", "");
	});

	// Workaround for target anchor not being in right place
	setTimeout(function () {
		with (document.location) hash && (hash = hash);
	}, 1);
});

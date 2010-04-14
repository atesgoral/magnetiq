$(function () {
	// Add panel backgrounds
	$(".panels li").each(function () {
		var a = $("a", this).get(0);
		var tokens = /p_(.+)/.exec(a.className);
		
		if (tokens) {
			a.style.backgroundImage =
				"url(panel_thumbs/" + tokens[1] + ".png)";
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
	
    // Add grid toggler
    $("#toggle_grid").click(function () {
        $("body").toggleClass("show_grid");
        return false;
    });

	// Workaround for target anchor not being in right place
	setTimeout(function () {
		with (document.location) if (hash) hash = hash;
	}, 1);

	// Get latest tweet
	/*$.ajax({
		url: "http://twitter.com/status/user_timeline/atesgoral.json?count=2",
		dataType: "jsonp",
		success: function (data) {
			$("#tweet_text").html(data[0].text);
			$("#tweet_time").html(data[0].created_at).attr("href",
				"http://twitter.com/atesgoral/status/" + data[0].id);
		}
	});*/
});

Cufon.replace("h1, h2");

// Collect meta data
var meta = {}, name;

$("meta").each(function () {
	var m = $(this);
	
	if (name = m.attr("name")) {
		meta[name] = m.attr("content");
	}
});

Cufon.replace("h2");
//var disqus_developer = 1;

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

	if ($("body").hasClass("index")) {
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
	}

    // Add grid toggler
    $("#toggle_grid").click(function () {
        $("body").toggleClass("show_grid");
        return false;
    });
	
	/*if ($("#latest_tweet").length) {
		// Get latest tweet
		$.ajax({
			url: "http://twitter.com/status/user_timeline/atesgoral.json?count=2",
			dataType: "jsonp",
			success: function (data) {
				$("#tweet_text").html(data[0].text);
				$("#tweet_time").html(data[0].created_at).attr("href",
					"http://twitter.com/atesgoral/status/" + data[0].id);
			}
		});
	}*/
	
	if ($("body").hasClass("disqus")) {
        $.getScript("http://disqus.com/forums/" + meta.DisqusShortname
            + "/get_num_replies.js?url0="
            + encodeURIComponent($("#comment_count").attr("href")));
	}
});

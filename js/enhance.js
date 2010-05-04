// Collect meta data
var meta = {}, name;

$("meta").each(function () {
	var m = $(this);
	
	if (name = m.attr("name")) {
		meta[name] = m.attr("content");
	}
});

Cufon.replace("h2");
Cufon.replace(".date_day");
var disqus_developer = 1;

$(function () {
	// Add popup handlers
	$("a.popup").click(function () {
		var attrs = {
			resizable: "no",
			scrollbars: "no",
			status: "no",
			dialog: "yes"
		};
		
		var dim = /\bd(\d+)x(\d+)\b/.exec(this.className);
		
		if (dim) {
			attrs.width = dim[1];
			attrs.height = dim[2];
		}
	
		var attrs_arr = [];
		
		for (var attr in attrs) {
			attrs_arr.push(attr + "=" + attrs[attr]);
		}
	
		window.open(this.href, "poly", attrs_arr.toString());

		return false;
	});
	
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
	
	// Get Disqus comment counts
	if ($("body").hasClass("disqus")) {
		var idx = 0, data = {}, nodes = [];

		$("a.comment_count").each(function () {
			var $a = $(this);
			var href = $a.attr("href");
			
			if (/#disqus_thread$/.test(href)) {
				data["url" + idx++] = href;
				nodes.push($a);
			}
		});
		
		function probe() {
			if (!/^\d/.test(nodes[0].html())) {
				setTimeout(probe, 1000);
				return;
			}
			
			$.each(nodes, function (i, $a) {
				var orig = $a.html(), tokens, sum = 0;

				while (tokens = /\d+/g.exec(orig)) {
					sum += parseInt(tokens[0]);
				}
				
				$a.attr("title", orig).html(sum)
					.css({ display: "block", opacity: 0 })
					.fadeTo("slow", 1);
			});
		}

		if (nodes.length) {
			$.ajax({
				url: "http://disqus.com/forums/" + meta.DisqusShortname
					+ "/get_num_replies.js",
				data: data,
				dataType: "script"
			});
			
			probe();
		}
	}
});

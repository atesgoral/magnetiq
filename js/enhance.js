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

//var disqus_developer = 1;

// Based on JavaScript Pretty Date by John Resig
function prettyDate(date) {
	var diff = (((new Date()).getTime() - date.getTime()) / 1000);
	var day_diff = Math.floor(diff / 86400);
			
	return day_diff == 0 && (
			diff < 60 && "Just now" ||
			diff < 120 && "1 minute ago" ||
			diff < 3600 && Math.floor( diff / 60 ) + " minutes ago" ||
			diff < 7200 && "1 hour ago" ||
			diff < 86400 && Math.floor( diff / 3600 ) + " hours ago") ||
		day_diff == 1 && "Yesterday" ||
		day_diff < 7 && day_diff + " days ago" ||
		day_diff < 31 && Math.ceil( day_diff / 7 ) + " weeks ago" ||
		date.toLocaleDateString();
}

function parseTwitterDate(s) {
	var date = new Date(s.replace(/^\w+ (\w+) (\d+) ([\d:]+) \+0000 (\d+)$/,
		"$1 $2 $4 $3 UTC"));
	return isNaN(date.getTime()) ? s : date;
}

$(function () {
    // Add grid toggler
    $("#toggle_grid").click(function () {
        $("body").toggleClass("show_grid");
        return false;
    });
	
	var title = meta.PostTitle || meta.PageTitle;

	// Tumblr 404 page customization workaround
	if (title === "Not Found") {
		$("#content h2").html(title);
		$("#content p:first").addClass("error")
			.html("Sorry, but what you're looking for is not here.")
			.after("<p>Please try retyping the URL. If you've come here by \
				clicking a link on another website, please report the link as \
				a broken link to the website owner.</p>\
				<p>You can also browse <a href=\"http://magnetiq.com/\">my \
				portfolio</a> or <a href=\"http://blog.magnetiq.com/\">blog</a>\
				to see if what you're looking for has moved to another \
				location.</p>");
		return;
	}

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
	
	if ($("body").hasClass("index")) {
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
	}
	
	// Get latest tweet
	if ($("#latest_tweet").length) {
		$.ajax({
			url: "http://twitter.com/status/user_timeline/atesgoral.json?count=2",
			dataType: "jsonp",
			success: function (data) {
				var tweet = data[0];
				var hyper = tweet.text
					.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1">$1</a>')
					.replace(/@(.+?)\b/g,
						'@<a href="http://twitter.com/$1">$1</a>');
				var date = parseTwitterDate(tweet.created_at);

				$("#tweet_text").html(hyper);
				$("#tweet_meta")
					.html('<a href="' + "http://twitter.com/atesgoral/status/"
						+ tweet.id + '" title="' + date.toLocaleString() + '">'
						+ prettyDate(date) + '</a> via ' + tweet.source);
			}
		});
	}
	
	// Get Disqus comment counts
	if ($("body").hasClass("disqus")) {
		if ($("body").hasClass("permalink")) {
			window.disqus_no_style = true;
			$.getScript("http://" + meta.DisqusShortname
				+ ".disqus.com/embed.js");
		}
	
		var idx = 0, data = {}, nodes = [];

		$("a.comment_count").each(function () {
			var $a = $(this);
			var href = $a.attr("href");
			
			if (/#disqus_thread$/.test(href)) {
				data["url" + idx++] = href;
				nodes.push($a);
			}
		});
		
		var probe_cnt = 0;
		
		function probe() {
			if (++probe_cnt > 20) {
				return;
			}

			if (!/^\d/.test(nodes[0].html())) {
				setTimeout(probe, 1000);
				return;
			}

			$.each(nodes, function (i, $a) {
				var orig = $a.html();
				
				$a.attr("title", orig).html(parseInt(orig))
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

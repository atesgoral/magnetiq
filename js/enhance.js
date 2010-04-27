Cufon.replace("h2");
var disqus_developer = 1;

$(function () {
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

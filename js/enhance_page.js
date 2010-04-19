$(function () {
    // Add grid toggler
    $("#toggle_grid").click(function () {
        $("body").toggleClass("show_grid");
        return false;
    });

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

Cufon.replace("h2");
SyntaxHighlighter.all();

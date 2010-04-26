if (meta.FontReplacement) {
    Cufon.replace("h2");
}

if (meta.PermalinkPage && meta.SyntaxHighlighting) {
    //SyntaxHighlighter.all();
}

$(function () {
    // Add grid toggler
    $("#toggle_grid").click(function () {
        $("body").toggleClass("show_grid");
        return false;
    });

    if (meta.PermalinkPage && meta.DisqusShortname) {
        $.getScript("http://disqus.com/forums/" + meta.DisqusShortname
            + "/get_num_replies.js?url0="
            + encodeURIComponent($("#comment_count").attr("href")));
    }
});
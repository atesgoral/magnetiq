$(document).ready(function () {
	$("#panels a").each(function (i, a) {
		(function (idx, panel) {
			a.style.backgroundImage =
				"url(project_images/" + panel + ".jpg)";
		})(i, /#(.+)/.exec(a.href)[1]);
	});
});

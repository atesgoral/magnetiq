$(document).ready(function () {
	$("#projects a").each(function (i, a) {
		(function (idx, project) {
			a.style.backgroundImage =
				"url(project_images/" + project + ".jpg)";
		})(i, /#(.+)/.exec(a.href)[1]);
	});
});

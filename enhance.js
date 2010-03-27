$(document).ready(function () {
	// Add panel backgrounds
	$(".panels a").each(function (i, a) {
		(function (idx, panel) {
			a.style.backgroundImage =
				"url(project_images/" + panel + ".jpg)";
		})(i, /#(.+)/.exec(a.href)[1]);
	});

	// Clone navigation
	var nav = $(".nav");
	$(".section_end").after(nav.clone());
});

$(document).ready(function () {
	$("#projects a").each(function (i, a) {
		(function (idx, project) {
			//console.log(project);

			a.bg =
			a.style.backgroundImage =
				"url(project_images/" + project + ".jpg)";
			
			$(a).hover(function () {
				var start = idx - idx % 3;
				
				console.log(project);
				
				$("#projects a").slice(start, start + 6).each(function (i, a) {
					//console.log(i);
					a.style.backgroundImage =
						"url(project_images/l_" + project + ".jpg)";
					var x = i % 3;
					var y = Math.floor(i / 3);
					console.log([x, y]);
					a.style.backgroundPosition = (-x * 240) + "px " + (-y * 296) + "px";
				});
			}, function () {
				var start = idx - idx % 3;
				
				console.log(project);
				
				$("#projects a").slice(start, start + 6).each(function (i, a) {
					//console.log(i);
					a.style.backgroundImage = a.bg;
					a.style.backgroundPosition = "0 0";
				});
			});
		})(i, /#(.+)/.exec(a.href)[1]);
	});
});

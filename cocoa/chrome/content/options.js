function installCallback(url, code) {
	alert("Installed " + url + code);
	//alert(g_cc_url);
}

function installConfigurator() {
	var params = {
		"Cocoa Configurator": {
			URL: "chrome://cocoa/content/cocoa_cfg.xpi",
		 	//IconURL: "chrome://cocoa/content/cocoa_cfg.png",
		 	Hash: "md5:50169736c2408d2b98bd32fadeef91a0",
		 	toString: function () { return this.URL; }
		}
	};
	
	InstallTrigger.install(params, installCallback);
}

document.getElementById("cc-url-auto").addEventListener("click",
		installConfigurator, false);
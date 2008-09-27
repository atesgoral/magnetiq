var Cocoa = {
	console: null,
	prefs: null,
	cc_url: "",
	
	ge: function (id) { return document.getElementById(id); },

	debug: function (str) {
		//alert("DEBUG: " + str);
		this.console.logStringMessage("[Cocoa] " + str);
	},
		
	haveMoreWindows: function () {
		var wm = Components.classes["@mozilla.org/appshell/window-mediator;1"]
				.getService(Components.interfaces.nsIWindowMediator);
		var enumerator = wm.getEnumerator(null);
		
		return enumerator.hasMoreElements() && enumerator.getNext() &&
				enumerator.hasMoreElements();
	},
	
	startup: function () {
		this.console = Components.classes["@mozilla.org/consoleservice;1"]
				.getService(Components.interfaces.nsIConsoleService);
		this.debug("startup()");
		this.progressBar = this.ge("cocoa-progressbar");
			
		// Register to receive notifications of preference changes
		
		this.prefs = Components.classes["@mozilla.org/preferences-service;1"]
				.getService(Components.interfaces.nsIPrefService)
				.getBranch("cocoa.");
		this.prefs.QueryInterface(Components.interfaces.nsIPrefBranch2);
		this.prefs.addObserver("", this, false);
		
		this.cc_url = this.prefs.getCharPref("cc_url");

		var container = gBrowser.tabContainer;
		container.addEventListener("TabOpen", this.tabAdded, false);
		container.addEventListener("TabClose", this.tabRemoved, false);
		
		for (var i = 0; i < gBrowser.browsers.length; i++) {
			this.addTab(gBrowser.getBrowserAtIndex(i));
		}		
	},
	
	shutdown: function (evt) {
		this.debug("shutdown()");
		
	  	try {
	  		if (this.haveMoreWindows()) {
	  			this.debug("Have more windows");
	  			return;
	  		}
	  		
	  		this.progressBar.hidden = false;
	
			return;
			
			this.debug("Logging off");
			this.logOff();
	
	  		evt.preventDefault();
		} catch (e) {
			Components.utils.reportError(e);
		}
	},

	observe: function (subject, topic, data) {
		this.debug("observe()");
		if (topic != "nsPref:changed") {
			return;
		}
		
		switch (data) {
		case "cc_url":
			this.cc_url = this.prefs.getCharPref("cc_url");
			break;
		}
	},
	
	tabAdded: function (evt) {
		Cocoa.debug("tabAdded()");
		Cocoa.addTab(evt.target.linkedBrowser);
	},
	
	tabRemoved: function (evt) {
		Cocoa.debug("tabRemoved()");
		var tab = evt.target.linkedBrowser;
	},
	
	addTab: function (tab) {
		this.debug("addTab() " + tab.currentURI.spec);
	},
	
	logOff: function () {
		this.debug("logOff()");
		var httpRequest = new XMLHttpRequest();
	
		httpRequest.onreadystatechange = function () {
			try {
				this.progressBar.value = httpRequest.readyState * 25;
				
				if (httpRequest.readyState == 4) {
					if (httpRequest.status == 200) {
				  		this.debug("Logged out");
				  	} else {
				  		this.debug("HTTP error while logging out");
				  	}
				  	
					close();
				}
			} catch (e) {
				Components.utils.reportError(e);
				//close(); // Only when logging off due to window close!
			}
		};
	
		httpRequest.open("GET", this.cc_url + "/index.jsp?page=Logout", true);
		httpRequest.send(null);
	},
	
	toggleDisable: function () {
		this.debug("toggleDisable()");
	},
	
	showOptions: function () {
		window.openDialog("chrome://cocoa/content/options.xul", "Preferences",
				"chrome,titlebar,toolbar,centerscreen,modal");
	}
};

window.addEventListener("load", function () { Cocoa.startup(); }, false);
window.addEventListener("unload", function () { Cocoa.shutdown(); }, false);
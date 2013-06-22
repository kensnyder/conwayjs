(function(window, document) { "use strict";
	var prefixes = false;
	if ('requestFullscreen' in document.documentElement) {
		prefixes = {
			requestfullscreen: 'requestfullscreen',
			exitFullscreen:    'exitFullscreen',
			fullscreenchange:  'fullscreenchange',
			status:            'fullscreen'
		};
	}
	else if (('mozRequestFullScreen' in document.documentElement) && document.mozFullScreenEnabled) {
		prefixes = {
			requestfullscreen: 'mozRequestFullScreen',
			exitFullscreen:    'mozCancelFullScreen',
			fullscreenchange:  'mozfullscreenchange',
			status:            'mozFullScreen'
		};
	}
	else if ('webkitRequestFullScreen' in document.documentElement) {
		prefixes = {
			requestfullscreen: 'webkitRequestFullScreen',
			exitFullscreen:    'webkitCancelFullScreen',
			fullscreenchange:  'webkitfullscreenchange',
			status:            'webkitIsFullScreen'
		};		
	}
	if (!!prefixes) {
		window.Fullscreen = {
			supported: function() {
				return true;
			},
			request: function() {
				document.documentElement[prefixes.requestfullscreen]();
				return this;
			},
			exit: function() {
				document[prefixes.exitFullscreen]();
				return this;
			},
			status: function() {
				return !!document[prefixes.status];
			},
			onchange: function(handler) {
				document.addEventListener(prefixes.fullscreenchange, handler, false);
				return this;
			},
			offchange: function(handler) {
				document.removeEventListener(prefixes.fullscreenchange, handler, false);
				return this;
			},
			getPrefixes: function() {
				return prefixes;
			}		
		};
	}
	else {
		window.Fullscreen = {
			supported: function() {
				return false;
			},
			request: function() {
				return this;
			},
			exit: function() {
				return this;
			},
			status: function() {
				return false;
			},
			onchange: function() {
				return this;
			},
			offchange: function() {
				return this;
			},
			getPrefixes: function() {
				return {};
			}		
		};
	}
}(window, document));
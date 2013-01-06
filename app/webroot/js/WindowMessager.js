function WindowMessager(partnerWindow, partnerDomain) {
	this.partner = {
		window: partnerWindow.contentWindow || partnerWindow,
		domain: partnerDomain || '*'
	};
	this.origin = window.location.protocol + '//' + window.location.hostname;
	this.setup();
}

WindowMessager.prototype = {
	setup: function() {
		this.callbacks = [];
		this.messages = [];
//		this.on('__WindowMessagerResendAction__', function(payload, event) {
//			this.callbacks.filter(function(callback) {
//				if (callback.eventName == eventName) {
//					callback.handler(payload, event);
//				}
//			});
//		}.bind(this));
		this.on('__WindowMessagerResendRequest__', function(eventName, event) {
			this.messages.filter(function(message) {
				if (message.eventName == eventName) {
					this.partner.window.postMessage(JSON.stringify(message), this.origin);
				}
				return false;
			}.bind(this));
		}.bind(this));
	},
	on: function(eventName, handler) {
		if (typeof eventName == 'object') {
			for (var name in eventName) {
				this.on(name, eventName[name]);
			}
			return this;
		}
		var callback = {
			eventName: eventName,
			handler: handler,
			listener: function(event) {
				try {
					if (this.partner.domain != '*' && this.partner.domain != event.origin) {
						return;
					}
					var message = JSON.parse(event.data);
					if (message.eventName === eventName) {
						handler(message.payload, event);
					}
				}
				catch(ignore) {}
			}.bind(this)
		};
		this.callbacks.push(callback);
		window.addEventListener('message', callback.listener, false);
		return this;
	},
	off: function(eventName, handler) {
		if (!eventName) {
			this.callbacks = [];
			return this;
		}
		this.callbacks = this.callbacks.filter(function(callback) {
			if (callback.eventName == eventName && (!handler || callback.handler == handler)) {
				window.removeEventListener(eventName, callback.listener, false);
				return false;
			}
			return true;
		}.bind(this));
		return this;
	},	
	requestResend: function(eventName) {
		this.partner.window.postMessage(JSON.stringify({
			eventName: '__WindowMessagerResendRequest__',
			payload: eventName
		}), this.origin);
		return this;
	},
	send: function(eventName, payload) {
		var message = {
			eventName: eventName,
			payload: payload
		};
		this.messages.push(message);
		this.partner.window.postMessage(JSON.stringify(message), this.origin);
		return this;
	},
	purgeMessage: function() {
		this.messages = [];
	}
};
function Modal(options) {
	this.options = $.extend({}, Modal.defaultOptions, options || {});
	this.close = this.close.bind(this);
	this.resize = this.resize.bind(this);
	this.open();
}

Modal.defaultOptions = {
	width: 400,
	height: 300,
	html: {
		cover: '<div class="modal-cover"></div>',
		dialog: '<div class="modal-dialog">' +
			'<a class="modal-close"></a>' +
			'<iframe class="modal-content"></iframe>' +
		'</div>'
	}
};

Modal.openModals = [];

Modal.close = function() {
	if (Modal.openModals.length == 0) {
		return null;
	}
	else {
		var modal = Modal.openModals[Modal.openModals.length];
		modal.close();
		return modal;
	}
};

Modal.prototype = {
	open: function() {
		Modal.openModals.push(this);
		// get elements
		var size = this.getSize();
		this.renderCover(size);
		this.renderDialog(size);
		$(window).resize(this.resize)
	},
	renderCover: function(size) {
		// add cover
		this.$cover = $(this.options.html.cover)
			.css({
				position: 'absolute',
				top: '0',
				left: '0',
				width: size.viewportWidth + 'px',
				height: size.viewportHeight + 'px',
				opacity: '0'
			})
			.click(this.close)
			.appendTo(document.body)
			.animate({
				opacity: '1'
			}, 300);
		;
		return this;
	},
	renderDialog: function(size) {
		// add dialog
		this.$dialog = $(this.options.html.dialog)
			.css({
				position: 'absolute',
				top: size.originTop + 'px',
				left: size.originLeft + 'px',
				width: size.originWidth + 'px',
				height: size.originHeight + 'px'			
			})
			.addClass('modal-loading')
			.appendTo(document.body)
			.animate({
				top: size.top + 'px',
				left: size.left + 'px',
				width: size.width + 'px',
				height: size.height + 'px'			
			}, 300);
		this.$close = this.$dialog.find('.modal-close').click(this.close);
		this.$content = this.$dialog.find('.modal-content')
			.css({
				width: size.width + 'px',
				height: (size.height - this.$close.outerHeight()) + 'px'
			})
			.prop('src', this.options.url)
			.load(function(event) {
				this.$dialog.removeClass('modal-loading');
				if (this.options.onload) {
					this.options.onload.call(this.$content[0], event);
				}
			}.bind(this));
		return this;
	},
	resize: function(options) {
		$.extend(this.options, options || {});
		var size = this.getSize();
		this.$cover.css({
			width: size.viewportWidth + 'px',
			height: size.viewportHeight + 'px'
		});
		this.$dialog.css({
			top: size.top + 'px',
			left: size.left + 'px',
			width: size.width + 'px',
			height: size.height + 'px'			
		});
		this.$content.css({
			width: size.width + 'px',
			height: (size.height - this.$close.outerHeight()) + 'px'			
		});
	},
	getSize: function() {
		var $win = $(window);
		// calculate size and position
		var size = {};
		// viewport
		size.viewportWidth = $win.width();
		size.viewportHeight = $win.height();
		// width and height
		if (typeof this.options.width == 'object') {
			size.width = size.viewportWidth - this.options.width.viewportMinus;
		}
		else {
			size.width = this.options.width;
		}
		if (typeof this.options.height == 'object') {
			size.height = size.viewportHeight - this.options.height.viewportMinus;
		}
		else {
			size.height = this.options.height;
		}
		// top and left
		size.top = (this.options.top == undefined ?
			Math.max(5, Math.floor( (size.viewportHeight - size.height) / 2 )) :
			this.options.top
		);
		size.left = (this.options.left == undefined ? 
			Math.max(5, Math.floor( (size.viewportWidth - size.width) / 2 )) :
			this.options.left
		);
		if (this.options.origin) {
			var $origin = $(this.options.origin);
			var offset = $origin.offset();
			size.originTop = offset.top;
			size.originLeft = offset.left;
			size.originWidth = $origin.outerWidth();
			size.originHeight = $origin.outerHeight();
		}
		else {
			size.originTop = Math.floor( (size.viewportWidth - size.width) / 2 );
			size.originLeft = Math.floor( (size.viewportHeight - size.height) / 2 );
			size.originWidth = 20;
			size.originHeight = 20;
		}
		return size;
	},
	close: function() {
		var size = this.getSize();
		this.$dialog.animate({
			top: size.originTop + 'px',
			left: size.originLeft + 'px',
			width: size.originWidth + 'px',
			height: size.originHeight + 'px'			
		}, 300, function() {
			this.$dialog.remove();
			this.$dialog = null;
		}.bind(this));
		this.$cover.animate({
			opacity: '1'
		}, 300, function() {
			this.$cover.remove();
			this.$cover = null;
		}.bind(this));
		Modal.openModals = Modal.openModals.filter(function(modal) {
			return modal !== this;
		}.bind(this));
		return this;
	}
};

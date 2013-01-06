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
				height: size.viewportHeight + 'px'
			})
			.click(this.close)
			.appendTo(document.body)
		;
		return this;
	},
	renderDialog: function(size) {
		// add dialog
		this.$dialog = $(this.options.html.dialog)
			.css({
				position: 'absolute',
				top: size.top + 'px',
				left: size.left + 'px',
				width: size.width + 'px',
				height: size.height + 'px'			
			})
			.addClass('modal-loading')
			.appendTo(document.body);
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
		return size;
	},
	close: function() {
		this.$dialog.remove();
		this.$cover.remove();
		this.$dialog = null;
		this.$cover = null;
		Modal.openModals = Modal.openModals.filter(function(modal) {
			return modal !== this;
		}.bind(this));
		return this;
	}
};

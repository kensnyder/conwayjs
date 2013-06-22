(function($) { "use strict";

	if (!document.createElement('canvas').getContext || !Function.prototype.bind || !Array.prototype.forEach || !document.addEventListener) {
		// we cannot help you, my friend!
	}

	var $panel = $('#panel');
	var $panelContent = $('#panel-content-area');
	var $back = $('.panel-back');
	var $shapeLabel = $('#shape-label');
	var $login = $('#loginButton');
	var $about = $('#logoLink, #aboutButton');
	var $fullscreen = $('#fullscreenButton');
	var $exitFullscreen = $('#shrinkButton');
	
	var startingPng = false;
	// overwrite shape picker
	GameControls.prototype._setupSeedSelect = function() {
		var button = this.elements.seedSelect;
		$(button).click(function() {
			this.disableKeyControls();
			if ($panelContent.html() == '') {						
				$panelContent.html('Loading...').load('/game_shape_categories/browse');
			}
			$panel.show();
		}.bind(this));
	};
	(function(start) {
		GameControls.prototype.start = function() {
			startingPng = controls.toPng();
			start.call(this);
		};
	}(GameControls.prototype.start));
	(function(clear) {
		GameControls.prototype.clear = function() {
			$shapeLabel.text('');
			startingPng = false;
			clear.call(this);
		};
	}(GameControls.prototype.clear));
	function sendShapeDetails() {
		function serializeSelect(element) {
			var serial = {
				selectedIndex: element.selectedIndex,
				options: []
			};
			$.each(element.options, function(i, option) {
				serial.options.push({value:option.value, text:option.text});
			});
			return serial;
		}
		var newPng = controls.toPng();
		var options = {
			blockSize: serializeSelect(controls.elements.blockSizeSelect),
			interval: serializeSelect(controls.elements.intervalSelect),
			rule: controls.elements.ruleSelect.options[controls.elements.ruleSelect.selectedIndex].value
		};
		new WindowMessager(this)
			.send('setCurrentImg', newPng)
			.send('setStartingImg', startingPng || newPng)
			.send('setBoardOptions', options)
		;
	}
	// overwrite the save function
	GameControls.prototype.save = function() {
		this.stop();
		if (this.game.numPoints == 0) {
			alert('Before saving, please choose a shape or click squares to add cells.');
			return false;
		}				
		var modal = new Modal({
			width: 1000,
			height: 400,
			url: '/game_shapes/save',
			origin: this.elements.saveButton,
			onload: sendShapeDetails
		});
	};
	$panel.delegate('.panel-close', 'click', function(evt) {
		evt.preventDefault();
		$panel.hide();
		controls.enableKeyControls();
	});
	$panelContent.delegate('.shape .item-nav-link', 'click', function(evt) {
		evt.preventDefault();
		evt.stopPropagation();
		var $link = $(this);
		$('.item-box').removeClass('focused');
		$link.parents('.item-box:eq(0)').addClass('focused');
		var href = $link.prop('href');
		$.ajax({
			url: href
		}).done(function(shape) {
			controls.clear();
			GameShapes.add(controls, shape.spec);
			if (shape.name) {
				$shapeLabel.text('Shape: ' + shape.name);
			}
			controls.renderer.draw();
			startingPng = controls.toPng();
		});
		$panel.hide();
		controls.enableKeyControls();
	});
	$panelContent.delegate('.category .item-nav-link', 'click', function(evt) {
		evt.preventDefault();
		var href = $(this).prop('href');
		$panelContent.empty().html('Loading...').load(href);
		$back.show();
	});
	$panelContent.delegate('form.search', 'submit', function(evt) {
		evt.preventDefault();
		evt.stopPropagation();
		var $form = $(this);
		var target = $form.prop('target');
		var url = target + '?' + $form.serialize();
		$panelContent.empty().html('Loading...').load(url);
		$back.show();
	});
	$back.click(function() {
		$panelContent.html('Loading...').load('/game_shape_categories/browse');
		$back.hide();
	});
	if (Fullscreen.supported()) {
		$fullscreen.click(function() {
			Fullscreen.request();
		});
		$exitFullscreen.click(function() {
			Fullscreen.exit();
		});
		var handleFullscreenChange = function() {
			if (Fullscreen.status()) {
				$fullscreen.hide();
				$exitFullscreen.show();
			}
			else {
				$fullscreen.show();
				$exitFullscreen.hide();			
			}
		}
		handleFullscreenChange();
		Fullscreen.onchange(handleFullscreenChange);
	}
	else {
		$fullscreen.hide();
		$exitFullscreen.hide();
	}
	$about.click(function() {
		controls.stop();
		var modal = new Modal({
			width: 700,
			height: {viewportMinus:20},
			url: '/about',
			origin: $(this).get(0)
		});
	});	
	$login.click(function() {
		controls.stop();
		var modal = new Modal({
			width: 400,
			height: 250,
			url: '/users/login',
			origin: $(this).get(0)
		});
	});	
	
	var controls = new GameControls(
		document.getElementById('controls'),
		document.getElementById('board')
	);
	controls.startButtonText = 'Start';
	controls.pauseButtonText = 'Pause';
	controls.stop();
	// starting shape
	var rle = "3o10bo20bo$o2bo2bo6bo20bo18b$o2bo6b2obo2bo5b2o6b3obo5b2o2b3o3b2ob$3o2b2o2bo3bobo8bo4bo4b3o5bobo2bobo2bo$o5bo2bo3b2o7b3o5b2o2bo2bo2b3obo2bob4o$o5bo2bo3bobo5bo2bo7bobo2bobo2bob3o2bo3b$o4b3o2b2obo2bo5b3o4b3o2bo2bo2b3obo5b3o$44bo8b$44bo8b2$13bo8b2o10bo18b$13bo9bo3bo6bo18b$b2o2b3o3b3o5b2o2bo7b2obo2bo15b$3bobo2bobo2bo4bo4bo2b2o2bo3bobo16b$b3obo2bobo2bo4bo4bo3bo2bo3b2o17b$o2bobo2bobo2bo4bo4bo3bo2bo3bobo16b$b3obo2bo2b3o5b2o2b2ob3o2b2obo2bo15b4$6bo13bo32b$6bo13bo32b$b3ob3o2b2o2b3o2b3o31b$o5bo5bobo2bo2bo32b$b2o3bo3b3obo5bo32b$3bo2bo2bo2bobo5bo32b$3o4bo2b3obo6bo31b!";
	controls.game.setRle(rle);
	controls.centerShapeOnBoard();
	startingPng = controls.toPng();
	
	window.controls = controls;
})(jQuery);

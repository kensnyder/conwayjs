(function($) { "use strict";

	var $panel = $('#panel');
	var $panelContent = $('#panel-content-area');
	var $back = $('.panel-back');
	var $shapeLabel = $('#shape-label');
	var startingPng = false;
	// overwrite shape picker
	GameControls.prototype._setupSeedSelect = function() {
		var button = this.elements.seedSelect;
		button.value = 'Pick Shape';
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
		var href = $(this).prop('href');
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
	var controls = new GameControls(
		document.getElementById('controls'),
		document.getElementById('board')
	);
	// starting shape
	var points = [[0,0],[0,1],[0,2],[0,3],[0,4],[0,5],[0,6],[1,0],[2,0],[3,1],[3,2],[2,3],[1,3],[6,3],[6,4],[6,5],[6,6],[5,6],[7,6],[9,4],[9,5],[10,6],[11,6],[13,6],[13,5],[13,4],[13,3],[13,2],[13,1],[13,0],[14,4],[15,5],[15,3],[16,6],[16,2],[21,5],[22,4],[23,4],[22,6],[23,6],[24,6],[24,5],[24,4],[24,3],[23,2],[22,2],[30,6],[31,6],[32,5],[31,4],[29,3],[30,4],[30,2],[31,2],[29,6],[32,2],[34,6],[34,5],[34,4],[34,3],[34,2],[34,1],[34,0],[35,3],[36,3],[37,4],[37,5],[37,6],[39,5],[40,6],[41,6],[42,6],[42,5],[42,3],[41,2],[40,2],[40,4],[41,4],[42,4],[44,6],[44,5],[44,4],[44,3],[44,2],[45,2],[46,2],[47,3],[47,4],[46,5],[45,5],[44,7],[44,8],[49,3],[49,4],[49,5],[50,6],[51,6],[52,6],[50,2],[51,2],[50,4],[51,4],[52,4],[52,3],[1,12],[2,12],[3,13],[3,14],[3,15],[3,16],[2,16],[1,16],[1,14],[2,14],[0,15],[5,16],[5,15],[5,14],[5,13],[5,12],[6,12],[7,12],[8,13],[8,14],[8,15],[8,16],[10,13],[10,14],[10,15],[11,16],[12,16],[13,16],[13,15],[13,14],[13,13],[13,12],[13,11],[13,10],[11,12],[12,12],[18,13],[18,14],[18,15],[9,3],[10,2],[11,2],[5,3],[6,1],[19,16],[20,16],[19,12],[20,12],[22,10],[23,10],[23,11],[23,12],[23,13],[23,14],[23,15],[23,16],[24,16],[26,13],[27,13],[27,14],[27,15],[26,16],[27,16],[28,16],[27,11],[30,15],[30,14],[30,13],[31,12],[32,12],[31,16],[32,16],[34,10],[34,11],[34,12],[34,13],[34,14],[34,15],[34,16],[35,14],[36,13],[37,12],[36,15],[37,16],[1,22],[2,22],[3,22],[0,23],[1,24],[2,24],[3,25],[2,26],[1,26],[0,26],[7,26],[6,20],[6,21],[6,22],[6,23],[6,24],[6,25],[5,22],[7,22],[10,22],[11,22],[12,23],[12,24],[12,25],[12,26],[11,26],[10,26],[9,25],[10,24],[11,24],[14,22],[14,23],[14,24],[14,25],[14,26],[16,22],[17,23],[15,22],[19,22],[20,22],[20,20],[20,21],[20,23],[20,24],[20,25],[21,26],[21,22]];
	var x = 33;
	var y = 17;
	points.forEach(function(xy) {
		controls.game.addPoint(x + xy[0], y + xy[1]);
	});
	controls.renderer.draw();
	startingPng = controls.toPng();
})(jQuery);

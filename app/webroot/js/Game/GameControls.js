(function(window, document) {
	"use strict";

	/**
	 * Class to wire DOM elements to control the game
	 * @class GameControls
	 * @constructor
	 * @param {HTMLElement} controls  A DOM element that contains all the controls
	 * @param {HTMLElement} board  The DOM element to which to render the board using canvas
	 */
	window.GameControls = function(controls, board) {
		this.options = {
			board: board
		};
		this.elements = {
			controls: controls,
			board: board
		};
		[].slice.call(controls.getElementsByTagName('*')).forEach(function(element) {
			if (!element.id) {
				return;
			}
			this.elements[element.id] = element;
		}.bind(this));
		this.initGame();
		this.setup();
	};

	/**
	 * The current generation number
	 * @property {Number} tick
	 */
	/**
	 * The Game object
	 * @property {Game} game
	 */
	/**
	 * The GameRenderer object
	 * @property {GameRenderer} renderer
	 */
	/**
	 * The game options set by the DOM controls
	 * @property {Object} options
	 * @property {HTMLElement} options.board  The DOM element to which to render the board using canvas
	 * @property {Number} options.interval  The number of milliseconds to wait between generations
	 */
	/**
	 * The text to show on the start button
	 * @property {String} startButtonText
	 */
	/**
	 * The text to show on the pause button
	 * @property {String} pauseButtonText
	 */
	/**
	 * The DOM elements that control the game, indexed by id attribute
	 * @property {Object} elements
	 * @property {HTMLElement} elements.autoSize  The button that triggers auto sizing board to fit current pattern
	 * @property {HTMLElement} elements.seedSelect  The drop down to choose a starting shape
	 * @property {HTMLElement} elements.ruleSelect  The drop down to set the birth-death rule
	 * @property {HTMLElement} elements.intervalSelect  The drop down to set number of milliseconds to wait between generations
	 * @property {HTMLElement} elements.visitedSelect  The drop down to set the color to use to paint "visited" points
	 * @property {HTMLElement} elements.gridlinesSelect  The drop down to enable and disable gridlines
	 * @property {HTMLElement} elements.blocksizeSelect  The drop down to set size of each block
	 * @property {HTMLElement} elements.clearButton  The button to clear all points from the board
	 * @property {HTMLElement} elements.resetButton  The button to reset the board to its starting state
	 * @property {HTMLElement} elements.saveButton  The button that saves the current state
	 * @property {HTMLElement} elements.startButton  The button that starts the game
	 * @property {HTMLElement} elements.optionsSummary  The element that shows a summary of the currently chosen options
	 * @property {HTMLElement} elements.optionsButton  The element that when clicked opens the list of options
	 * @property {HTMLElement} elements.options  The element that contains the list of options
	 * @property {HTMLElement} elements.optionsClose  The element that when clicked hides the list of options
	 * @property {HTMLElement} elements.board  The element that contains the canvas board
	 */
	window.GameControls.prototype = {
		/**
		 * Setup all the controls
		 * @method setup
		 */
		setup: function setup() {
			this.initialGrid = null;
			this.keyControlsEnabled = true;
			this.startButtonText = 'Start \u25B6';
			this.pauseButtonText = 'Pause \u220E\u220E';			
			this._setupSeedSelect();
			this._setupIntervalSelect();
			this._setupBlockSizeSelect();
			this._setupGridlinesSelect();
			this._setupRuleSelect();
			this._setupVisitedSelect();
			this._setupOptionsButton();
			this._setupStartButton();
			this._setupBoardClick();
			this._setupSaveButton();
			this._setupClearButton();
			this._setupResetButton();
			this._setupFitToShape();
			this._setupPan();
		},
		/**
		 * Initialize the Game object and the GameRenderer object
		 * @method initGame
		 * @return {GameControls}
		 * @chainable
		 */
		initGame: function initGame() {
			this.tick = 0;
			this.game = new Game();
			this.renderer = new GameRenderer(this.game, this.options);
			this.renderer.draw();
			this.updateOptionsSummary();
			return this;
		},		
		/**
		 * Increment the generation and draw the new board
		 * @method _tickAndDraw
		 */
		_tickAndDraw: function _tickAndDraw() {
			this.tick++;
			this.game.tick();
			this.renderer.draw();
			if (this.game.numPoints == 0) {
				this.stop();
			}
			// garbage collect offscreen points every 10s
			if (Math.floor(+new Date / 1000) % 10 == 0) {
				this.renderer.killOffscreenPoints();
			}
		},		
		/**
		 * Setup the auto size button
		 * @method _setupFitToShape
		 */
		_setupFitToShape: function _setupFitToShape() {
			this.elements.fitToShape.onclick = this.fitToShape.bind(this);
			window.addEventListener('keydown', function(evt) {
				if (!this.keyControlsEnabled) {
					return;
				}
				if (evt.which === 70) {
					// letter F
					this.fitToShape();
				}
			}.bind(this), false);
		},
		/**
		 * Size the board to fit all the points
		 * @method fitToShape
		 * @return {GameControls}
		 * @chainable
		 */
		fitToShape: function fitToShape() {
			var minmax = this.getBoardMinMax();
			var min = minmax[0];
			var max = minmax[1];
			var shapeSize = {x: max[0]-min[0]+1, y: max[1]-min[1]+1};
			// use 0.9 to give some breathing room around the sizes
			var blockSize = Math.min(this.renderer.grid.width * 0.8 / shapeSize.x, this.renderer.grid.height * 0.8 / shapeSize.y).toFixed(2);
			if      (blockSize < 0.25) blockSize = 0.25;
			else if (blockSize < 0.33) blockSize = 0.33;
			else if (blockSize < 0.50) blockSize = 0.50;
			else if (blockSize < 1.00) blockSize = 1.00;
			else blockSize = Math.floor(blockSize);
			if (blockSize < 4) {
				this.disableGridlines();
			}
			else {
				this.enableGridlines();
			}
			if (blockSize > 20) {
				blockSize = 20;
			}
			this.setBlockSize(blockSize);
			this.centerShapeOnBoard();
			return this;
		},
		/**
		 * Disable key listeners (e.g. if there is an input box on the screen)
		 * @method disableKeyControls
		 * @return {GameControls}
		 * @chainable
		 */		
		disableKeyControls: function disableKeyControls() {
			this.keyControlsEnabled = false;
			return this;
		},
		/**
		 * Enable key listeners
		 * @method enableKeyControls
		 * @return {GameControls}
		 * @chainable
		 */
		enableKeyControls: function enableKeyControls() {
			this.keyControlsEnabled = true;
			return this;
		},
		/**
		 * Set up the panning controls (arrow keys and mousewheel)
		 * @method _setupPan
		 */
		_setupPan: function _setupPan() {
			document.addEventListener('keydown', this._handleArrowKeys.bind(this));
			document.addEventListener('mousewheel', this._handleWheel.bind(this));
			document.addEventListener('DOMMouseScroll', this._handleWheel.bind(this));
		},
		/**
		 * Respond to arrow keys by panning the board
		 * @method _handleArrowKeys
		 * @param {HTMLEvent} evt  The keydown event
		 */
		_handleArrowKeys: function _handleArrowKeys(evt) {
			if (!this.keyControlsEnabled) {
				return;
			}
			var incX = Math.round(this.renderer.boardSize.x * 0.1);
			var incY = Math.round(this.renderer.boardSize.y * 0.1);
				 if (evt.which == 37) this.pan(-incX,0); // left
			else if (evt.which == 38) this.pan(0,-incY); // up
			else if (evt.which == 39) this.pan(incX,0);  // right
			else if (evt.which == 40) this.pan(0,incY);  // down			
		},
		/**
		 * Respond to the mousewheel by panning the board (vertically; with shift: horizontally)
		 * @method _handleWheel
		 * @param {HTMLEvent} evt  The mousewheel or DOMMouseScroll event
		 */
		_handleWheel: function _handleWheel(evt) {
			if (!this.keyControlsEnabled) {
				return;
			}
			if (evt.ctrlKey) {
				return;
			}
			if (!evt.target.tagName || evt.target.tagName.toUpperCase() != 'CANVAS') {
				return;
			}
			evt.preventDefault();
			var dir = evt.shiftKey ? 'x' : 'y';
			var inc = Math.round(this.renderer.boardSize[dir] * 0.05);
			inc = inc || 1;
			var delta = evt.wheelDelta || evt.detail;
			if (delta > 0) {
				if (dir == 'y') {
					this.pan(0, inc);
				}
				else {
					this.pan(inc, 0);
				}
			}
			else if (delta < 0) {
				if (dir == 'y') {
					this.pan(0, -inc);
				}
				else {
					this.pan(-inc, 0);
				}
			}
		},
		/**
		 * Set up the seed select drop down
		 * @method _setupSeedSelect
		 */
		_setupSeedSelect: function _setupSeedSelect() {
			var select = this.elements.seedSelect;
			var idx = 0;
			select.options[idx++] = new Option('','');
			for (var pct = 10; pct < 100; pct += 10) {
				select.options[idx++] = new Option('Random board - '+pct+'% full', 'seed-0.'+pct);
			}
			GameShapeLibrary.forEach(function(shape) {
				select.options[idx++] = new Option('Shape - ' + shape.name, 'addShape-'+shape.id);
			});
			// add some from local storage
			select.selectedIndex = 0;
			select.onchange = this._handleSeedSelect.bind(this);
		},
		/**
		 * Seed the board with random points
		 * @method seed
		 * @param {Number} ratio  A number between 0 and 1 representing how many of the points to randomly pick
		 * @return {GameControls}
		 * @chainable
		 */
		seed: function seed(ratio) {
			var x, y, numPoints = Math.floor(this.renderer.boardSize.x * this.renderer.boardSize.y * ratio * 0.60 * 0.60);
			for (var i = 0; i < numPoints; i++) {
				x = Math.floor(this.renderer.boardSize.x * Math.random() * 0.60) + Math.floor(this.renderer.boardSize.x * 0.20);
				y = Math.floor(this.renderer.boardSize.y * Math.random() * 0.60) + Math.floor(this.renderer.boardSize.y * 0.20);
				if (this.game.isAlive(x,y)) {
					i--;
				}
				else {
					this.game.addPoint(x, y);
				}
			}
			return this;
		},
		/**
		 * Respond to the seed select drop down
		 * @method _handleSeedSelect
		 */
		_handleSeedSelect: function _handleSeedSelect() {
			var select = this.elements.seedSelect;
			select.blur();
			this.stop();
			this.clear();
			var value = select.options[select.selectedIndex].value;
			if (value) {
				var parts = value.split('-');
				this[parts[0]](parts[1]);
			}
			this.renderer.draw();
			select.selectedIndex = 0;
		},		
		/**
		 * Add a shape to the game board
		 * @method addShape
		 * @param {String} name  The name of the shape found in the GameShapeLibrary array
		 * @return {GameControls}
		 * @chainable
		 */
		addShape: function(name) {	
			GameShapes.add(this, name, this.renderer.boardSize.x, this.renderer.boardSize.y);
			return this;
		},	
		/**
		 * Listen for rule select drop down changes
		 * @method _setupRuleSelect
		 */
		_setupRuleSelect: function _setupRuleSelect() {
			this._populateRuleSelect();
			this.elements.ruleSelect.onchange = this._handleRuleSelect.bind(this);
			this.setRule('23/3');
			this.updateOptionsSummary();
		},
		_populateRuleSelect: function _populateRuleSelect() {
			var select = this.elements.ruleSelect;
			select.options[0] = new Option('Custom...', 'custom');
			select.options[0].title = 'Type in a custom rule.';
			var groups = {};
			['Chaotic','Stable','Expansive','Explosive'].forEach(function(type) {
				var grp = document.createElement('optgroup');
				groups[type.toLowerCase()] = grp;
				grp.label = type;
				select.appendChild(grp);
			});
			GameRules.forEach(function(rule) {
				var opt = new Option(padRule(rule.rule) + ' ' + rule.name, rule.rule);
				opt.title = rule.desc;
				groups[rule.type].appendChild(opt);
			});
		},
		/**
		 * Respond to rule selection
		 * @method _handleRuleSelect
		 */
		_handleRuleSelect: function _handleRuleSelect() {
			var select = this.elements.ruleSelect;
			select.blur();
			this.setRule(getSelectValue(select));
			this.updateOptionsSummary();
		},
		/**
		 * Set the rulestring 
		 * @method setRule
		 * @param {String} value  The rulestring (e.g. "23/3" or "B3/S23")
		 * @return {GameControls}
		 * @chainable
		 */
		setRule: function setRule(value) {
			if (value == 'custom') {
				value = prompt('Enter rulestring: (e.g. "23/3" or "B3/S23")');
				if (!value) {
					return this;
				}
				try {
					this.game.setRuleString(value);
				}
				catch (e) {
					alert(e.message);
					return this;
				}
				this.elements.ruleSelect.options[this.elements.ruleSelect.options.length] = new Option(value, value);
			}
			this.game.setRuleString(value);			
			setSelectValue(this.elements.ruleSelect, this.game.rule.numeric);
			this.updateOptionsSummary();
			return this;
		},
		/**
		 * Listen for change to visited select
		 * @method _setupVisitedSelect
		 */
		_setupVisitedSelect: function _setupVisitedSelect() {
			var select = this.elements.visitedSelect;
			var idx = 0;
			select[idx] = new Option('(none)','');
			select[idx++].style.backgroundColor = '#fffff';
			select[idx] = new Option('blue','#cff1ff');
			select[idx++].style.backgroundColor = '#cff1ff';
			select[idx] = new Option('gray','#f0f0f0');
			select[idx++].style.backgroundColor = '#f0f0f0';
			select[idx] = new Option('yellow','#fffed9');
			select[idx++].style.backgroundColor = '#fffed9';
			select[idx] = new Option('green','#ceffde');
			select[idx++].style.backgroundColor = '#ceffde';
			select[idx] = new Option('orange','#fff0cf');
			select[idx++].style.backgroundColor = '#fff0cf';
			select.selectedIndex = 0;
			select.onchange = function() {
				var hex = select.options[select.selectedIndex].value;
				if (hex === '') {
					this.renderer.drawVisited = false;
				}
				else {
					this.renderer.drawVisited = true;
					this.renderer.visitedColor = hex;
				}
			}.bind(this);
		},
		/**
		 * Listen for a click on the options button
		 * @method _setupOptionsButton
		 */
		_setupOptionsButton: function _setupOptionsButton() {
			var button = this.elements.optionsButton;
			var div = this.elements.options;
			button.addEventListener('click', function(evt) {
				evt.preventDefault();
				div.style.display = div.style.display == 'none' ? '' : 'none';
			}, false);
			this.elements.board.addEventListener('click', function() {
				div.style.display = 'none';
			}, false);
			this.elements.optionsClose.addEventListener('click', function(evt) {
				evt.preventDefault();
				div.style.display = 'none';
			}, false);
		},
		/**
		 * Listen for a change in the interval drop down
		 * @method _setupIntervalSelect
		 */
		_setupIntervalSelect: function _setupIntervalSelect() {
			var select = this.elements.intervalSelect;
			select.options[0] = new Option('Max',    '0.00');
			select.options[1] = new Option('≈40fps', '40.00');
			select.options[1] = new Option('≈25fps', '25.00');
			select.options[2] = new Option('≈10fps', '10.00');
			select.options[3] = new Option('≈5fps',  '5.00');
			select.options[4] = new Option('≈3fps',  '3.00');
			select.options[5] = new Option('≈2fps',  '2.00');
			select.options[6] = new Option('≈1fps',  '1.00');
			select.options[7] = new Option('≈1/2fps','0.50');
			select.onchange = this._handleIntervalSelect.bind(this);
			this.setSpeed(0);
			window.addEventListener('keydown', function(evt) {
				if (!this.keyControlsEnabled || (evt.which != 188 && evt.which != 190)) {
					return;
				}
				var select = this.elements.intervalSelect;
				var idx = select.selectedIndex;
				if (evt.which == 190 && idx > 0) {
					// > key
					select.selectedIndex--;
				}
				else if (evt.which == 188 && idx < select.options.length - 1) {
					// < key
					select.selectedIndex++;
				}
				this.setSpeed(select.options[select.selectedIndex].value);
			}.bind(this), true);
		},
		/**
		 * Respond to a change in the interval drop down
		 * @method _handleIntervalSelect
		 */
		_handleIntervalSelect: function _handleIntervalSelect() {
			var select = this.elements.intervalSelect;
			select.blur();
			this.setSpeed(select.options[select.selectedIndex].value);
		},
		/**
		 * Set the number of frames per second to render
		 * @method setSpeed
		 * @param {Number} fps  Set to 0 for max
		 * @return {GameControls}
		 * @chainable
		 */
		setSpeed: function setSpeed(fps) {
			fps = parseFloat(fps);
			this.options.interval = fps < 0.001 ? 0 : (1000 / fps) - 4;
			if (this.isRunning) {
				this.stop();
				this.start();
			}
			setSelectValue(this.elements.intervalSelect, fps.toFixed(2));
			this.updateOptionsSummary();
			return this;
		},
		/**
		 * Listen for changes to the block size drop down
		 * @method _setupBlockSizeSelect
		 */
		_setupBlockSizeSelect: function _setupBlockSizeSelect() {
			var select = this.elements.blockSizeSelect;
			var idx = 0;
			select.options[idx++] = new Option('1/4','0.25');
			select.options[idx++] = new Option('1/3','0.33');
			select.options[idx++] = new Option('1/2','0.50');
			for (var hw = 1; hw <= 20; hw++) {
				select.options[idx++] = new Option(hw, hw + '.00');
			}
			select.onchange = this._handleBlockSizeSelect.bind(this);
			this.setBlockSize(6);
			window.addEventListener('keydown', this._handleBlockSizeKeys.bind(this), false);	
		},
		/**
		 * Respond to a change in block size drop down
		 * @method _handleBlockSizeSelect
		 */
		_handleBlockSizeSelect: function _handleBlockSizeSelect() {
			var select = this.elements.blockSizeSelect;
			select.blur();
			var newSize = select.options[select.selectedIndex].value;
//			if (newSize == 'auto') {
//				this._fitInterval = setInterval(this.fitToShape.bind(this), 2000);
//				return;
//			}
//			else {
//				clearInterval(this._fitInterval);
//			}
			if (newSize < 4) {
				this.disableGridlines();
			}
			else {
				this.enableGridlines();
			}
			this.setBlockSize(newSize);
			this.centerShapeOnBoard();			
		},
		_handleBlockSizeKeys: function _handleBlockSizeKeys(evt) {
			if (!this.keyControlsEnabled) {
				return;
			}
			var select = this.elements.blockSizeSelect;
			if (
				(evt.which === 173 || evt.which === 109)
				&& select.selectedIndex > 0
			) {
				select.selectedIndex--;
				this._handleBlockSizeSelect();
			}
			else if (
				(evt.which === 61 || evt.which == 107)
				&& select.selectedIndex + 1 < select.length
			) {
				select.selectedIndex++;
				this._handleBlockSizeSelect();
			}			
		},
		centerShapeOnBoard: function centerShapeOnBoard() {
			var minmax = this.getBoardMinMax();
			var min = minmax[0];
			var max = minmax[1];
			var shapeSize = {x: max[0]-min[0]+1, y: max[1]-min[1]+1};
			var startX = Math.ceil((this.renderer.boardSize.x - shapeSize.x) / 2);
			var startY = Math.ceil((this.renderer.boardSize.y - shapeSize.y) / 2);
			this.pan(min[0] - startX, min[1] - startY);
			return this;
		},
		/**
		 * Set the size of each block
		 * @method setBlockSize
		 * @param {Number} size  The size of the block in pixels
		 * @return {GameControls}
		 * @chainable
		 */
		setBlockSize: function setBlockSize(size) {
			size = +size;
			var oldBoardSize = this.renderer.boardSize;
			this.renderer.setBlockSize(size);
			var newBoardSize = this.renderer.boardSize;
			this.panForResize(oldBoardSize, newBoardSize);
			this.renderer.drawAll();
			setSelectValue(this.elements.blockSizeSelect, size.toFixed(2));
			this.updateOptionsSummary();
			return this;
		},
		/**
		 * Pan the board such as to accomodate a board resize
		 * @method panForResize
		 * @param {Object} oldBoardSize
		 * @param {Object} oldBoardSize.x  The width of the old board
		 * @param {Object} oldBoardSize.y  The height of the old board
		 * @param {Object} newBoardSize
		 * @param {Object} newBoardSize.x  The width of the new board
		 * @param {Object} newBoardSize.y  The height of the new board
		 * @return {GameControls}
		 * @chainable
		 */
		panForResize: function panForResize(oldBoardSize, newBoardSize) {
			this.pan(Math.floor(oldBoardSize.x - newBoardSize.x), Math.floor(oldBoardSize.y - newBoardSize.y));
			return this;
		},
		/**
		 * Listen to changes in a gridlines select and populate options
		 * @method _setupGridlinesSelect
		 */
		_setupGridlinesSelect: function _setupGridlinesSelect() {
			var select = this.elements.gridlinesSelect;
			select.options[0] = new Option('On', '1');
			select.options[1] = new Option('Off', '0');
			select.options[2] = new Option('White', 'white');
			this.enableGridlines();
			select.onchange = this._handleGridlinesSelect.bind(this);
		},
		/**
		 * Respond to change in gridlines select
		 * @method _handleGridlinesSelect
		 */
		_handleGridlinesSelect: function _handleGridlinesSelect() {
			var select = this.elements.gridlinesSelect;
			this.renderer.gridlinesColor = '#d0d0d0';
			if (select.selectedIndex === 0) {
				this.renderer.useGridlines = 0; // force enable to re-render
				this.enableGridlines();
			}
			else if (select.selectedIndex === 1) {
				this.disableGridlines();
			}
			else {
				this.renderer.gridlinesColor = '#ffffff';
				this.renderer.useGridlines = 0; // force enable to re-render
				this.enableGridlines();
				select.selectedIndex = 2; // reset value
			}
			select.blur();
		},
		/**
		 * Enable gridlines on the board
		 * @method enableGridlines
		 * @return {GameControls}
		 * @chainable
		 */
		enableGridlines: function enableGridlines() {
			if (this.renderer.useGridlines == 1) {
				return this;
			}
			var oldBoardSize = this.renderer.boardSize;
			this.renderer.useGridlines = 1;
			this.renderer.drawAll();
			var newBoardSize = this.renderer.boardSize;
			this.panForResize(oldBoardSize, newBoardSize);
			setSelectValue(this.elements.gridlinesSelect, '1');
			return this;
		},
		/**
		 * Disable gridlines on the board
		 * @method disableGridlines
		 * @return {GameControls}
		 * @chainable
		 */
		disableGridlines: function disableGridlines() {
			if (this.renderer.useGridlines == 0) {
				return this;
			}
			var oldBoardSize = this.renderer.boardSize;
			this.renderer.useGridlines = 0;
			this.renderer.drawAll();
			var newBoardSize = this.renderer.boardSize;		
			this.panForResize(oldBoardSize, newBoardSize);
			setSelectValue(this.elements.gridlinesSelect, '0');
			return this;
		},
		/**
		 * Listen for a click on the start/pause button and the spacebar
		 * @method _setupStartButton
		 */
		_setupStartButton: function _setupStartButton() {
			var button = this.elements.startButton;
			var handle = this._handleStartButton.bind(this);
			button.onclick = handle;
			window.addEventListener('keydown', function(evt) {
				if (!this.keyControlsEnabled) {
					return;
				}
				if (evt.which == 32 || evt.which == 13) {
					// spacebar or enter
					handle(evt);
				}
				else if (evt.which == 84) {
					// letter t
					if (this.isRunning) {
						this.stop();
					}
					this._tickAndDraw();
				}
			}.bind(this), false);
			this.stop();
		},
		/**
		 * Respond to start button or enter/spacebar
		 * @method _handleStartButton
		 */
		_handleStartButton: function _handleStartButton() {
			var button = this.elements.startButton;
			button.blur();
			if (this.isRunning) {
				this.stop();
			}
			else if (this.game.numPoints == 0) {
				alert('Before starting, please choose a shape or click squares to add cells.');
			}
			else {
				this.start();
			}			
		},
		/**
		 * Pause the game
		 * @method stop
		 * @return {GameControls}
		 * @chainable
		 */
		stop: function stop() {
			var button = this.elements.startButton;
			button.value = this.startButtonText;
			document.body.className = document.body.className.replace(/ game-(started|paused)/g, '') + ' game-paused';
			this.isRunning = false;
			clearInterval(this._intervalId);
			return this;
		},	
		/**
		 * Start/unpause the game
		 * @method start
		 * @return {GameControls}
		 * @chainable
		 */		
		start: function start() {
			if (this.initialGrid === null && this.game.numPoints > 0) {
				this.initialGrid = JSON.parse(JSON.stringify(this.game.grid));
			}
			var button = this.elements.startButton;
			button.value = this.pauseButtonText;
			document.body.className = document.body.className.replace(/ game-(started|paused)/g, '') + ' game-started';
			this.isRunning = true;
			this._startTime = +new Date;
			this._intervalId = setInterval(this._tickAndDraw.bind(this), this.options.interval);
			return this;
		},
		/**
		 * Listen for clicks and mousedown on the board for drawing and erasing points
		 * @method _setupBoardClick
		 */
		_setupBoardClick: function _setupBoardClick() {
			var board = this.elements.board;
			var which;
			var drawAtCursor = function(evt) {
				var x = Math.floor(
					evt.pageX / 
					(this.renderer.blockSize + (this.renderer.useGridlines ? 1 : 0))
				);
				var y = Math.floor(
					evt.pageY / 
					(this.renderer.blockSize + (this.renderer.useGridlines ? 1 : 0))
				);
				if (which == 3) {
					// right click
					this.game.removePoint(x,y);
				}
				else {
					this.game.addPoint(x,y);
				}
				this.renderer.draw();
			}.bind(this);
			board.onmousedown = function(evt) {
				which = evt.which; // check if we are in right or left click
				board.onmousemove = drawAtCursor;
			};
			board.onmouseup = function() {
				which = null;
				board.onmousemove = null;
			};
			board.onclick = function(evt) {
				which = evt.which;
				drawAtCursor(evt);
			}
			board.oncontextmenu = function(evt) {
				which = 3;
				evt.preventDefault();
				drawAtCursor(evt);
			};
		},
		/**
		 * Listen for save button click
		 * @method _setupSaveButton
		 */
		_setupSaveButton: function _setupSaveButton() {
			this.elements.saveButton.onclick = this.save.bind(this);
		},
		/**
		 * Get the minimum [x,y] and maximum [x,y] that the alive points span
		 * @method getBoardMinMax
		 * @param {Array} [points]  An array of [x,y] coordinates; defaults to the this.game.getPoints()
		 * @return {Array}  Array of min max in the form [[a,b],[x,y]]
		 */
		getBoardMinMax: function getBoardMinMax(points) {
			var min = [Infinity,Infinity];
			var max = [-Infinity,-Infinity];
			if (!points) {
				points = this.game.getPoints();
			}
			if (points.length == 0) {
				return [[0,0],[0,0]];
			}
			points.forEach(function(xy) {
				if      (xy[0] < min[0]) min[0] = xy[0];
				else if (xy[0] > max[0]) max[0] = xy[0];
				if      (xy[1] < min[1]) min[1] = xy[1];
				else if (xy[1] > max[1]) max[1] = xy[1];
			});
			return [min,max];
		},
		/**
		 * Return the points and size of the current board state
		 * @method boardToShape
		 * @return {Object}  Contains key size with [width,height] and key points, an array of points
		 */
		boardToShape: function boardToShape() {
			var points = this.game.getPoints();
			// first find min and max
			var minmax = this.getBoardMinMax(points);
			var min = minmax[0], max = minmax[1];
			// then gather all the points relative to 0,0
			var newPoints = [];
			points.forEach(function(xy) {
				newPoints.push([xy[0]-min[0],xy[1]-min[1]]);
			});
			return {
				size: [max[0]-min[0]+1, max[1]-min[1]+1],
				points: newPoints
			};
		},
		/**
		 * console.log the points in the current board state and return them
		 * @method save
		 */
		save: function save() {
			var shape = this.boardToShape();
			console.log(JSON.stringify(shape));
			return shape;
		},
		/**
		 * Update the text in the options summary element based on current game parameters
		 * @method updateOptionsSummary
		 * @return {GameControls}
		 * @chainable
		 */
		updateOptionsSummary: function updateOptionsSummary() {
			var rule = getSelectValue(this.elements.ruleSelect).replace(/ .+$/, '');
			var speed = getSelectText(this.elements.intervalSelect);
			var blockSize = this.renderer.blockSize;
			this.elements.optionsSummary.innerHTML = '<strong>' + rule + '</strong>, Speed: <strong>' + speed + '</strong>, Zoom: <strong>' + blockSize + '</strong>';
			return this;
		},
		/**
		 * Get the board size, blocksize, and gridline state to use to render the current board state to png
		 * For example, a larger board has smaller blocks and no gridlines
		 * @method getPngOptions
		 * @param {Object} shape  A shape as in that returned by this.boardToShape
		 * @return {Object}  contains keys maxBoardSize, blockSize, useGridlines
		 */
		getPngOptions: function getPngOptions(shape) {
			var largestSide = Math.max(shape.size[0], shape.size[1]);
			var sizes = [
				{
					maxBoardSize: 28,
					blockSize: 6,
					useGridlines: 1
				},
				{
					maxBoardSize: 49,
					blockSize: 3,
					useGridlines: 1
				},
				{
					maxBoardSize: 100,
					blockSize: 2,
					useGridlines: 0
				},
				{
					maxBoardSize: Infinity,
					blockSize: 1,
					useGridlines: 0
				}
			];
			for (var i = 0; i < sizes.length; i++) {
				if (largestSide <= sizes[i].maxBoardSize) {
					return sizes[i];
				}
			}
		},
		/**
		 * Return the shape complete with png data URI representing the current board state
		 * @method toPng
		 * @params {Object} options  with keys maxBoardSize, blockSize, useGridlines
		 * @return {Object}  with keys size, points, png
		 */
		toPng: function toPng(options) {
			var shape = this.boardToShape();
			options = $.extend(this.getPngOptions(shape), options || {});
			var renderer = {};
			// build up an object comaptible with GameRenderer
			renderer.useGridlines = options.useGridlines;
			renderer.gridlinesColor = '#d0d0d0';
			renderer.drawVisited = false;
			renderer.grid = document.createElement('canvas');
			renderer.grid.ctx = renderer.grid.getContext('2d');
			renderer.grid.width = (shape.size[0] * (options.blockSize + options.useGridlines)) + 1;
			renderer.grid.height = (shape.size[1] * (options.blockSize + options.useGridlines)) + 1;
			renderer.board = renderer.grid;
			renderer.blockSize = options.blockSize;
			renderer.game = {grid:{}};
			shape.points.forEach(function(xy) {
				renderer.game.grid[xy[0]+','+xy[1]] = 1;
			});
			GameRenderer.prototype.drawBoard.call(renderer);
			renderer.grid.ctx.strokeStyle = renderer.gridlinesColor;
			if (renderer.useGridlines) {
				GameRenderer.prototype._drawGridLines.call(renderer, 'width'); // vertical lines	
				GameRenderer.prototype._drawGridLines.call(renderer, 'height'); // horizontal lines
			}
			shape.png = renderer.grid.toDataURL('image/png');
			return shape;
		},
		/**
		 * Setup listener for click on clear button
		 * @method _setupClearButton
		 */
		_setupClearButton: function _setupClearButton() {
			this.elements.clearButton.onclick = this.clear.bind(this);
		},
		/**
		 * Setup listener for click on clear button
		 * @method _setupResetButton
		 */		
		_setupResetButton: function _setupResetButton() {
			this.elements.resetButton.onclick = this.reset.bind(this);
			window.addEventListener('keydown', function(evt) {
				if (!this.keyControlsEnabled) {
					return;
				}				
				if (evt.which === 82) { // letter R
					this.reset();
				}
			}.bind(this), false);
		},
		/**
		 * Clear all points from the board
		 * @method clear
		 * @return {GameControls}
		 * @chainable
		 */
		clear: function clear() {
			this.stop();
			this.game.clear();
			this.renderer.visitedPoints = {};
			this.renderer.drawVisitedBoard();
			this.renderer.draw();
			this.initialGrid = null;
			return this;
		},
		/**
		 * Reset to initial state before first start
		 * @method reset
		 * @return {GameControls}
		 * @chainable
		 */		
		reset: function reset() {
			this.stop();
			this.game.clear();
			this.renderer.visitedPoints = {};
			// set to initialGrid
			if (!!this.initialGrid && typeof this.initialGrid == 'object') {
				this.game.setGrid(this.initialGrid);
			}
			this.centerShapeOnBoard();
			this.renderer.drawVisitedBoard();
			this.renderer.draw();
			return this;
		},
		/**
		 * Pan by the given ratio
		 * @method panRatio
		 * @param {Number} byRatio  A number between 0 and 1 representing how many blocks to pan left (negative number) or right (positive number)
		 * @return {GameControls}
		 * @chainable
		 */
		panRatio: function panRatio(byRatio) {
			var byX = Math.round(this.renderer.boardSize.x * byRatio,0);
			var byY = Math.round(this.renderer.boardSize.y * byRatio,0);
			this.pan(byX, byY);
			return this;
		},
		/**
		 * Pan by the given number of blocks
		 * @method panRatio
		 * @param {Number} byX  The number of blocks to pan left or right
		 * @param {Number} byY  The number of blocks to pan up or down
		 * @return {GameControls}
		 * @chainable
		 */		
		pan: function pan(byX, byY) {
			var newGrid = {}, xy;
			for (var point in this.game.grid) {
				xy = point.split(',');
				newGrid[(xy[0]-byX)+','+(xy[1]-byY)] = 1;
			}
			this.game.grid = newGrid;
			var newVisited = {};
			for (point in this.renderer.visitedPoints) {
				xy = point.split(',');
				newVisited[(xy[0]-byX)+','+(xy[1]-byY)] = true;
			}
			this.renderer.visitedPoints = newVisited;
			this.renderer.drawVisitedBoard();
			this.renderer.draw();
			return this;
		}
	};
	
	// helper to set a select element to the given value
	function setSelectValue(select, toValue) {
		toValue = '' + toValue;
		for (var i = 0, len = select.length; i < len; i++) {
			if (select.options[i].value === toValue) {
				select.selectedIndex = i;
				return i;
			}
		}
		select.selectedIndex = 0;
		return false;
	}
	
	// helper to get the value of a select element
	function getSelectValue(select) {
		if (select.selectedIndex < 0) {
			return '';
		}
		return select.options[select.selectedIndex].value;
	}
	
	// helper to get the display text for the given select element
	function getSelectText(select) {
		if (select.selectedIndex < 0) {
			return '';
		}		
		return select.options[select.selectedIndex].text;
	}
	
	// helper to pad the rulestring so that they all have the same length
	function padRule(rule) {
		var full = '           ';
		return rule + full.slice(rule.length).replace(/ /g, '\xA0');
	}
	
}(window, document));
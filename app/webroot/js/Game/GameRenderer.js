(function(exports) {
	"use strict";

	/**
	 * Render the game state to canvas
	 * @class GameRenderer
	 * @constructor
	 * @param {Game} game  The game to render
	 * @param {Object} options  The options to use
	 */
	exports.GameRenderer = function(game, options) {
		this.game = game;
		this.container = options.board;
		this.container.style.position = 'relative';
		this.useGridlines = ('useGridlines' in options) ? options.useGridlines : true;
		this.gridlinesColor = options.gridlinesColor || '#d0d0d0';
		this.drawVisited = ('drawVisited' in options) ? options.drawVisited : false;
		this.visitedColor = options.visitedColor || '#ceffde'; // green
		this.setup();
		this.setBlockSize(options.blockSize || 6);
		this.garbageDistance = 30;
		this.perf = {};
	}

	/**
	 * The Game object
	 * @property {Game} game
	 */
	/**
	 * The container to which to add the canvas elements
	 * @property {HTMLElement} container
	 */
	/**
	 * True to use gridlines
	 * @property {Boolean} useGridlines
	 */
	/**
	 * Hex code or rgb code to use as gridlines color
	 * @property {String} gridlinesColor
	 */
	/**
	 * True to draw visited points
	 * @property {Boolean} drawVisited
	 */
	/**
	 * Hex code or rgb code to use as color of visited points
	 * @property {String} visitedColor
	 */
	/**
	 * How far offscreen points must be to be garbage collected periodically
	 * @property {Number} garbageDistance
	 */
	/**
	 * Canvas to display gridlines
	 * @property {HTMLCanvasElement} grid
	 */
	/**
	 * Canvas to display visited squares
	 * @property {HTMLCanvasElement} visitedBoard
	 */
	/**
	 * Canvas to display squares
	 * @property {HTMLCanvasElement} board
	 */
	/**
	 * Performance metrics such as fps
	 * @param {Object} perf
	 * @param {Object} perf.lastDrawTime  Millisecond timestamp of beginning of last render
	 * @param {Object} perf.fps  The current frames-per-second measure
	 * @param {Object} perf.lastGeneration  The generation number since the last perf check
	 */
	GameRenderer.prototype = {
		/**
		 * Render the board
		 * @method draw
		 * @return {GameRenderer}
		 * @chainable
		 */
		draw: function draw() {
			this.drawBoard();
			this.drawStats();
			return this;
		},
		/**
		 * Get the current measure of frames per second
		 * @method getFps
		 * @return {Number}
		 */
		getFps: function getFps() {
			var elapsed, now;
			if (this.game.numPoints === 0) {
				return 0;
			}
			if (this.game.generation === 0) {
				this.perf.lastDrawTime = null;
				this.perf.fps = 0;
				this.perf.lastGeneration = 0;
			}
			else if (this.perf.lastDrawTime) {
				now = +new Date;
				elapsed = now - this.perf.lastDrawTime;
				if (elapsed > 1000) {
					this.perf.fps = Math.round(1000 / elapsed * (this.game.generation - this.perf.lastGeneration), 0);
					this.perf.lastGeneration = this.game.generation;
					this.perf.lastDrawTime = now;
				}
			}
			else {
				this.perf.lastDrawTime = +new Date;
			}
			return this.perf.fps;
		},
		/**
		 * Create the canvas elements and setup listeners for window resizing
		 * @method setup
		 * @return {GameRenderer}
		 * @chainable
		 */
		setup: function setup() {
			this.container.innerHTML = '';
			// grid canvas
			this.grid = this._makeCanvas();
			this.drawGrid();
			window.addEventListener('resize', this.drawGrid.bind(this), false);
			// visited canvas
			this.visitedBoard = this._makeCanvas();
			this.visitedPoints = {};
			window.addEventListener('resize', this.drawVisitedBoard.bind(this), false);
			// board canvas
			this.board = this._makeCanvas();
			window.addEventListener('resize', this.drawBoard.bind(this), false);
			return this;
		},
		/**
		 * Render a blank board
		 * @method clear
		 * @return {GameRenderer}
		 * @chainable
		 */
		clear: function clear() {
			this.visitedPoints = {};
			this.drawAll();
			return this;
		},
		/**
		 * Render all three boards: grid, visited board, points board
		 * @method drawAll
		 * @return {GameRenderer}
		 * @chainable
		 */		
		drawAll: function drawAll() {
			this.drawGrid();
			this.drawVisitedBoard();
			this.drawBoard();			
			return this;
		},
		/**
		 * Create a canvas element, append it to our container, set to largest possible size, and listen for window resize
		 * @method _makeCanvas
		 * @return {HTMLCanvasElement}  The canvas element
		 */
		_makeCanvas: function _makeCanvas() {
			var canvas = document.createElement('canvas');
			canvas.style.position = 'absolute';
			canvas.ctx = canvas.getContext('2d');
			this.container.appendChild(canvas);
			var setSize = function() {
				canvas.height = this.container.offsetHeight;
				canvas.width = this.container.offsetWidth;
			}.bind(this);
			window.addEventListener('resize', setSize, false);
			setSize();
			return canvas;
		},
		/**
		 * Set the size of the block in pixels
		 * @method setBlockSize
		 * @params {Number} pixels
		 * @return {GameRenderer}
		 * @chainable
		 */
		setBlockSize: function setBlockSize(pixels) {
			this.blockSize = pixels;
			this.boardSize = {
				x: Math.floor(this.grid.width / (this.blockSize + (this.useGridlines ? 1 : 0))),
				y: Math.floor(this.grid.height / (this.blockSize + (this.useGridlines ? 1 : 0)))
			};
			return this;
		},
		/**
		 * Draw all the gridlines on the gridlines canvas
		 * @method drawGrid
		 * @return {GameRenderer}
		 * @chainable
		 */		
		drawGrid: function drawGrid() {
			this.grid.ctx.clearRect(0, 0, this.grid.width, this.grid.height);
			if (!this.useGridlines) {
				return;
			}
			this.grid.ctx.strokeStyle = this.gridlinesColor;
			this._drawGridLines('width'); // vertical lines	
			this._drawGridLines('height'); // horizontal lines
			return this;
		},
		/**
		 * Draw the vertical or horizontal grid lines
		 * @method _drawGridlines
		 * @params {String} prop  "width" for vertical lines, "height" for horizontal lines
		 */			
		_drawGridLines: function _drawGridLines(prop) {
			this.grid.ctx.beginPath();
			for (var i = 0; i <= this.grid[prop]; i += this.blockSize+1) {
				if (prop == 'width') { // vertical lines	
					this.grid.ctx.moveTo(i+0.5, 0);
					this.grid.ctx.lineTo(i+0.5, this.grid.height);
				}
				else if (prop == 'height') { // horizontal lines
					this.grid.ctx.moveTo(0, i+0.5);
					this.grid.ctx.lineTo(this.grid.width, i+0.5);
				}		
			}
			this.grid.ctx.stroke();
		},
		/**
		 * Draw the board containing visited points
		 * @method drawVisitedBoard
		 * @return {GameRenderer}
		 * @chainable
		 */				
		drawVisitedBoard: function drawVisitedBoard() {
			this.visitedBoard.ctx.clearRect(0, 0, this.visitedBoard.width, this.visitedBoard.height);
			if (!this.drawVisited) {
				return this;
			}
			this.visitedBoard.ctx.fillStyle = this.visitedColor;
			var w = this.blockSize + (this.useGridlines ? 1 : 0);
			var h = this.blockSize + (this.useGridlines ? 1 : 0);
			var xy;
			for (var point in this.visitedPoints) {
				xy = point.split(',');
				this.visitedBoard.ctx.fillRect(
					xy[0] * w + 1,
					xy[1] * h + 1,
					this.blockSize,
					this.blockSize
				);
			}
			return this;			
		},
		/**
		 * Draw the board containing currently alive points
		 * @method drawVisitedBoard
		 * @return {GameRenderer}
		 * @chainable
		 */			
		drawBoard: function drawBoard() {	
			this.board.ctx.fillStyle = '#000';
			this.board.ctx.clearRect(0, 0, this.board.width, this.board.height);
			var xy;
			var w = this.blockSize + (this.useGridlines ? 1 : 0);
			var h = this.blockSize + (this.useGridlines ? 1 : 0);
			for (var point in this.game.grid) {
				xy = point.split(',');
				this.board.ctx.fillRect(
					xy[0] * w + 1,
					xy[1] * h + 1,
					this.blockSize,
					this.blockSize
				);
			}
			if (this.drawVisited) {
				this.visitedBoard.ctx.fillStyle = this.visitedColor;
				for (point in this.game.grid) {
					if (this.visitedPoints[point] === undefined) {
						xy = point.split(',');
						this.visitedPoints[point] = true; 
						this.visitedBoard.ctx.fillRect(
							xy[0] * w + 1,
							xy[1] * h + 1,
							this.blockSize,
							this.blockSize
						);
					}	
				}
			}
		},
		/**
		 * Get the stats to display on the stats overlay
		 * @method getStats
		 * @return {Object}
		 */
		getStats: function getStats() {
			return {
				Board: this.boardSize.x + 'x' + this.boardSize.y,
				Tick: this.game.generation,
				Cells: this.game.numPoints,
				FPS: this.getFps() || '-'
			};
		},
		/**
		 * Draw the stats overlay box
		 * @method drawStats
		 * @return {GameRenderer}
		 * @chainable
		 */
		drawStats: function drawStats() {
			this.board.ctx.fillStyle = 'rgba(255,255,255,0.75)';
			this.board.ctx.fillRect(0,22,115,64);
			this.board.ctx.fillStyle = 'rgba(30,62,246,0.80)';
			this.board.ctx.font = '10pt Arial';
			var stats = this.getStats();
			this.board.ctx.fillText('Board: ' + stats.Board, 6, 43);
			this.board.ctx.fillText('Tick: ' + stats.Tick, 6, 55);
			this.board.ctx.fillText('Cells: ' + stats.Cells, 6, 67);
			this.board.ctx.fillText('FPS: ' + stats.FPS, 6, 79);
			return this;
		},
		/**
		 * We call this method to periodically garbage collect points that are too far offscreen (this.garbageDistance)
		 * @method killOffscreenPoints
		 * @return {GameRenderer}
		 * @chainable
		 */
		killOffscreenPoints: function killOffscreenPoints() {
			this.game.getPoints().forEach(function _killPointIfOffscreen(xy) {
				if (xy[0] < -this.garbageDistance || xy[1] < -this.garbageDistance || xy[0] > this.boardSize.x + this.garbageDistance || xy[1] > this.boardSize.y + this.garbageDistance) {
					this.game.removePoint(xy[0], xy[1]);
				}
			}.bind(this));
			return this;
		}
	};
}(typeof exports === 'undefined' ? this : exports));
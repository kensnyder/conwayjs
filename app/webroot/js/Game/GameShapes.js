(function(exports) {
	"use strict";

	/**
	 * Static class to read and manipulate game shapes
	 * @class GameShapes
	 * @static
	 */
	exports.GameShapes = {
		/**
		 * Find a shape by name and return it
		 * @method find
		 * @static
		 * @param {String} name  The name of the shape to find
		 * @return {Object}  The specs for the shape or false if shape is unknown
		 */
		find: function find(name) {
			var idx = 0, shape;
			while ((shape = GameShapeLibrary[idx++])) {
				if (shape.id == name) {
					return shape;
				}
			}			
			return false;
		},
		/**
		 * Add a shape to a board
		 * @method add
		 * @static
		 * @param {GameControls} controls  The GameControls to use to display the shape
		 * @param {Object} shape  The specs for the shape to add
		 * @return {GameShapes}
		 * @chainable
		 */		
		add: function add(controls, shape) {
			if (typeof shape == 'string') {
				shape = this.find(shape);			
			}
			if (!shape) {
				return false;
			}			
			if (shape.random) {
				controls.seed(shape.ratio);
			}
			else {
				shape.pos = shape.pos || 'middle-center';
				shape.speed = shape.speed === undefined ? 0 : shape.speed;
				shape.rule = shape.rule || '23/3';
				controls.setRule(shape.rule);
				controls.setSpeed(shape.speed);				
				var x = this.getStartX(controls, shape);
				var y = this.getStartY(controls, shape);
				var points = this.getPoints(shape);
				points.forEach(function(xy) {
					controls.game.addPoint(x + xy[0], y + xy[1]);
				});
				if (shape.zoom) {
					controls.setBlockSize(shape.zoom);
					if (shape.zoom > 2) {
						controls.enableGridlines();
					}
					else {
						controls.disableGridlines();
					}
				}		
				else {
					controls.fitToShape();
				}				
			}
			return this;
		},
		/**
		 * Get the starting X coordinate based on the shape and its position metric ("top-left", "bottom-center", "middle-right", etc.)
		 * @method getStartX
		 * @static
		 * @param {GameControls} controls  The GameControls to use to display the shape
		 * @param {Object} shape  The specs for the shape to position
		 * @return {Number}
		 */		
		getStartX: function getStartX(controls, shape) {
			var padding = Math.floor(controls.renderer.boardSize.x * 0.075);
			switch (shape.pos.split('-')[1]) {
				case 'left':   return padding;
				default:
				case 'center': return Math.floor((controls.renderer.boardSize.x / 2) - (shape.size[0] / 2));
				case 'right':  return controls.renderer.boardSize.x - shape.size[0] - padding;
			}			
		},
		/**
		 * Get the starting & coordinate based on the shape and its position metric ("top-left", "bottom-center", "middle-right", etc.)
		 * @method getStartY
		 * @static
		 * @param {GameControls} controls  The GameControls to use to display the shape
		 * @param {Object} shape  The specs for the shape to position
		 * @return {Number}
		 */				
		getStartY: function getStartY(controls, shape) {
			var padding = Math.floor(controls.renderer.boardSize.y * 0.075);
			switch (shape.pos.split('-')[0]) {
				case 'top':    return padding;
				default:
				case 'middle': return Math.floor((controls.renderer.boardSize.y / 2) - (shape.size[1] / 2));
				case 'bottom': return controls.renderer.boardSize.y - shape.size[1] - padding;
			}
		},
		/**
		 * Get an array of points for a given shape (it may have rle string, points strins, points array)
		 * @method getPonts
		 * @static 
		 * @param {Object} shape  The specs for the shape
		 * @return {Array}
		 */
		getPoints: function getPonts(shape) {
			if (shape.rle) {
				return this.parseRle(shape.rle);
			}
			if (typeof shape.points === 'string') {
				return JSON.parse(shape.points);
			}
			// assume shape.points to be an array
			return shape.points;
		},
		/**
		 * Parse an RLE formatted shape specification into an array of points
		 * @method getPonts
		 * @static 
		 * @param {String} rle  The RLE 1.06 spec (e.g. b3o$3ob$bo)
		 * @return {Array}
		 */		
		parseRle: function parseRle(rle) {
			// convert RLE format to points
			// http://www.conwaylife.com/wiki/Run_Length_Encoded
			// b = dead
			// o = alive
			// $ = newline
			var relX = 0, relY = 0;
			var points = [];
			//shape.rle.replace(/^.+x = \d+, y = \d+.*(\n|\r)(.+)!.*$/, '$2').split(/(\d+b|\d+o|b|o|\$)/).forEach(function(token) {
			rle.split(/(\d+\D|\D)/).forEach(function(token) {
				if (token.trim() == '') {
					return '';
				}
				token.replace(/^(\d+)o$/, function($0, $1) {
					var max = +$1;
					for (var i = 0; i < max; i++) {
						points.push([relX++,relY]);
					}
				});
				token.replace(/^(\d+)b$/, function($0, $1) {
					relX += parseInt($1,10);
				});
				token.replace(/^(\d+)\$$/, function($0, $1) {
					relX = 0;
					relY += +$1;
				});
				if (token == 'o') {
					points.push([relX++,relY]);
				}
				else if (token == 'b') {
					relX++;
				}
				else if (token == '$') {
					relX = 0;
					relY++;
				}
				return '';
			});	
			return points;
		},
		toRle: function toRle(points) {
			
		}
	};
}(typeof exports === 'undefined' ? this : exports));
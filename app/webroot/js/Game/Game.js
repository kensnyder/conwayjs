(function(exports) {
	"use strict";

	/**
	 * An engine to run Conway's game of life
	 * @class Game
	 * @constructor
	 * @params {String} rule  The game rule in the form "B3/S23" or "23/2"
	 */
	exports.Game = function(rule) {
		this.setRuleString(rule || 'B3/S23');
		this.clear();
	}

	/**
	 * The grid of points in the form {"a,b":true,"x,y":true}
	 * @property {Object} grid
	 */
	/**
	 * The number of points on the grid
	 * @property {Number} numPoints
	 */
	/**
	 * The generation number since starting the game
	 * @property {Number}  generation;
	 */
	/**
	 * Information about the rule
	 * @property {Object} rule
	 * @property {String} rule.numeric  Rule in the form "23/2"
	 * @property {String} rule.bs  Rule in the form "B3/S23"
	 * @property {Object} rule.birth  A lookup with the number of neighbors that cause birth. e.g. {3:true}
	 * @property {Object} rule.survive  A lookup with the number of neighbors that cause survival. e.g. {2:true,3:true}
	 * @property {Object} rule.max  The max numer of neighbors that must be counted. e.g. for "B3/S23" max is 3
	 */
	exports.Game.prototype = {
		/**
		 * Initialize an empty grid
		 * @method clear
		 * @returns {Game}
		 * @chainable
		 */
		clear: function clear() {
			this.grid = {};
			this.numPoints = 0;
			this.generation = 0;
			return this;
		},
		/**
		 * Initialize the grid to the given grid object
		 * @method setGrid
		 * @params {Object} grid  A grid in the same form as is stored internally
		 * @returns {Game}
		 * @chainable
		 */
		setGrid: function setGrid(grid) {
			this.grid = grid;
			this.numPoints = Object.keys(grid).length;
			this.generation = 0;
			return this;
		},
		/**
		 * Add a single point to the grid
		 * @method addPoint
		 * @params {Number} x  The X coordinate
		 * @params {Number} y  The Y coordiante
		 * @returns {Game}
		 * @chainable
		 */
		addPoint: function addPoint(x,y) {
			// see http://jsperf.com/typeof-vs-in for why we use `=== undefined`
			if (this.grid[x+','+y] === undefined) {				
				this.grid[x+','+y] = true;
				this.numPoints++;
			}
			return this;
		},
		/**
		 * Add an array of points to the grid
		 * @method addPoints
		 * @params {Array} points  Coordinates in the form [[0,0],[1,1],[2,2]]
		 * @returns {Game}
		 * @chainable
		 */
		addPoints: function addPoints(points) {
			for (var i = 0, len = points.length; i < len; i++) {				
				this.addPoint(points[i][0], point[i][1]);
			}
			return this;
		},
		/**
		 * Manually remove a point from the grid
		 * @method removePoint
		 * @params {Number} x  The X coordinate
		 * @params {Number} y  The Y coordiante
		 * @returns {Game}
		 * @chainable
		 */
		removePoint: function removePoint(x,y) {
			if (this.grid[x+','+y] === undefined) {
				return this;
			}
			delete this.grid[x+','+y];
			this.numPoints--;
			return this;
		},
		/**
		 * Tell if a particular point on the grid is alive
		 * @method isAlive
		 * @params {Number} x  The X coordinate
		 * @params {Number} y  The Y coordiante
		 * @returns {Boolean}  True if the point is alive
		 */
		isAlive: function isAlive(x,y) {
			// see http://jsperf.com/typeof-vs-in for why we use `!== undefined`
			return this.grid[x+','+y] !== undefined;
		},
		/**
		 * Run the game for one generation
		 * @method tick
		 * @return {Number}  The current generation number
		 */
		tick: function tick() {
			var newGrid = {};
			var neighborCache = {};
			this.generation++;
			this.numPoints = 0;
			var xy, x, y, neighbors, n, nxy, nx, ny, cnt, isAlive;
			var survive = this.rule.survive;
			var birth = this.rule.birth;
			for (var point in this.grid) {
				if (point === undefined) {
					continue;
				}
				// get xy
				xy = point.split(',');
				x = +xy[0];
				y = +xy[1];
				// check self
				cnt = (neighborCache[point] !== undefined) ?
					neighborCache[point] :
					neighborCache[point] = this._neighborShortcount(x, y);					
				isAlive = this.grid[point] !== undefined;
				if (
					(isAlive && survive[cnt] !== undefined)
					|| (!isAlive && birth[cnt] !== undefined)
				) {
					newGrid[point] = true;
					this.numPoints++;
				}					
				// check neighbors
				neighbors = [
					[x-1,y-1],[x  ,y-1],[x+1,y-1],
					[x,  y  ],          [x+1,y  ],
					[x-1,y+1],[x  ,y+1],[x+1,y+1],
				];
				for (n = 0; n < 8; n++) {
					nx = neighbors[n][0];
					ny = neighbors[n][1];
					nxy = nx+','+ny;
					if (newGrid[nxy] !== undefined) {
						continue;
					}
					// see http://jsperf.com/typeof-vs-in for why we use `!== undefined`
					cnt = (neighborCache[nxy] !== undefined) ?
						neighborCache[nxy] :
						neighborCache[nxy] = this._neighborShortcount(nx, ny);					
					isAlive = this.grid[nxy] !== undefined;
					if (
						(isAlive && survive[cnt] !== undefined)
						|| (!isAlive && birth[cnt] !== undefined)
					) {
						newGrid[nxy] = true;
						this.numPoints++;
					}					
				}
			}
			this.grid = newGrid;
			return this.generation;
		},
		/**
		 * Set the game's rule
		 * @params {String} rulestring  The game rule in the form "B3/S23" or "23/2"
		 * @returns {Game}
		 * @chainable
		 */
		setRuleString: function setRuleString(rulestring) {
			this.rule = this.parseRuleString(rulestring);
			return this;
		},
		parseRuleString: function parseRuleString(rulestring) {
			var birth, survive, rule;
			if (!rulestring.match(/^B?[0-8]*\/S?[0-8]*$/)) {
				throw new Error('Invalid rulestring `' + rulestring + '`. Rulestring must be in the format "23/3" or "B3/S23".');
			}
			rule = {};
			var match = (/^B(\d+)\/S(\d+)$/i).exec(rulestring);
			if (match) {
				birth = match[1];
				survive = match[2];
			}
			else {
				match = rulestring.split('/');
				birth = match[1] || '';
				survive = match[0];
			}
			rule.numeric = survive + '/' + birth;
			rule.bs = 'B' + birth + '/S' + survive;
			rule.max = -1;
			rule.birth = {};
			rule.survive = {};
			birth.split('').forEach(function(digit) {
				if (digit > rule.max) {
					rule.max = +digit;
				}
				rule.birth[digit] = true;
			});
			if (rule.birth['0']) {
				throw new Error('Invalid rulestring `' + rulestring + '`. Birth of cells with zero neighbors is not supported.');
			}
			survive.split('').forEach(function(digit) {
				if (digit > rule.max) {
					rule.max = +digit;
				}
				rule.survive[digit] = true;
			});
			if (Object.keys(rule.birth).length + Object.keys(rule.survive).length === 0) {
				throw new Error('Invalid rulestring `' + rulestring + '`. There must be some birth or survival defined.');
			}
			return rule;
		},
		/**
		 * Convert the grid into an array of points
		 * @method getPoints
		 * @return {Array}  Coordinates in the form [[0,0],[1,1],[2,2]]
		 */
		getPoints: function getPoints() {
			var points = [], xy;
			for (var point in this.grid) {
				xy = point.split(',');
				points.push([+xy[0], +xy[1]]);
			}
			return points;
		},
		setPoints: function setPoints() {
			
		},
		/**
		 * Convert the grid into a JSON string
		 * @method serialize
		 * @return {String}
		 */
		serialize: function serialize() {
			return JSON.stringify(this.grid);
		},
		/**
		 * Set the grid from a JSON string
		 * @method unserialize
		 * @return {Game}
		 * @chainable
		 */
		unserialize: function unserialize(gridString) {
			this.setGrid(JSON.parse(gridString));
			return this;
		},
/**
		 * Get the minimum [x,y] and maximum [x,y] that the alive points span
		 * @method getBoundingBox
		 * @return {Object} In the form {size:[width,height], min:[a,b], max:[x,y]}
		 */
		getBoundingBox: function getBoundingBox() {
			var min = [Infinity,Infinity];
			var max = [-Infinity,-Infinity];
			if (this.numPoints === 0) {
				return {
					min : [0,0],
					max : [0,0],
					size: [0,0]
				};
			}
			var xy, x, y;
			for (var point in this.grid) {
				xy = point.split(',');
				x = +xy[0];
				y = +xy[1];
				if      (x < min[0]) min[0] = x;
				else if (x > max[0]) max[0] = x;
				if      (y < min[1]) min[1] = y;
				else if (y > max[1]) max[1] = y;
			}
			return {
				min : min,
				max : max,
				size: [max[0]-min[0]+1, max[1]-min[1]+1]
			};
		},	
		/**
		 * Parse an RLE formatted shape specification into an array of points
		 * @method getPonts
		 * @static 
		 * @param {String} rle  The RLE 1.06 spec (e.g. b3o$3ob$bo)
		 * @return {Array}
		 */		
		setRle: function setRle(rle) {
			// convert RLE format to points
			// http://www.conwaylife.com/wiki/Run_Length_Encoded
			// b = dead
			// o = alive
			// $ = newline
			var relX = 0, relY = 0;
			var grid = {};
			rle.split(/(\d+\D|\D)/).forEach(function(token) {
				if (token.trim() == '') {
					return '';
				}
				token.replace(/^(\d+)o$/, function($0, $1) {
					var max = +$1;
					for (var i = 0; i < max; i++) {
						grid[(relX++)+','+relY] = true;
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
					grid[(relX++)+','+relY] = true;
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
			this.setGrid(grid);
			return this;
		},
		getRle: function getRle() {
			var box = this.getBoundingBox();
			var x, y;
			var width = box.size[0];
			var rle = '';
			for (y = box.min[1]; y <= box.max[1]; y++) {
				// each row
				for (x = box.min[0]; x <= box.max[0]; x++) {
					// each column
					if (this.grid[x+','+y] === undefined) {
						rle += 'b'; // dead
					}
					else {
						rle += 'o'; // alive
					}					
				}
				rle += '$'; // newline at end of row
			}
			// use 2 newlines to represent a full empty line
			rle = rle.replace(new RegExp('b{' + width + '}\\$', 'g'), '$$');
			// count number of repeats and replace
			rle = rle.replace(/(b{3,}|o{3,}|\${3,})/g, function($0, $1) {
				return $1.length + $1.slice(0,1);
			});
			// remove dead cells preceeding a newline
			rle = rle.replace(/\d*b\$/, '$');
			// change final $ to !
			rle = rle.replace(/\$$/, '!');
			return rle;
		},
		/**
		 * Count the number of neighbors of a given point.
		 * Return early if the count is equal to this.rule.max
		 * @return {Number} The number of neighbors
		 */
		_neighborShortcount: function _neighborShortcount(x, y) {
			var neighbors = 0;
			// see http://jsperf.com/typeof-vs-in for why we use `!== undefined`
			if (this.grid[(x-1)+','+(y-1)] !== undefined) neighbors++;
			if (neighbors > this.rule.max) return neighbors;
			
			if (this.grid[(x)+','+(y-1)] !== undefined)   neighbors++;
			if (neighbors > this.rule.max) return neighbors;
			
			if (this.grid[(x+1)+','+(y-1)] !== undefined) neighbors++;
			if (neighbors > this.rule.max) return neighbors;
			
			if (this.grid[(x-1)+','+(y)] !== undefined)   neighbors++;
			if (neighbors > this.rule.max) return neighbors;
			
			if (this.grid[(x+1)+','+(y)] !== undefined)   neighbors++;
			if (neighbors > this.rule.max) return neighbors;
			
			if (this.grid[(x-1)+','+(y+1)] !== undefined) neighbors++;
			if (neighbors > this.rule.max) return neighbors;
			
			if (this.grid[(x)+','+(y+1)] !== undefined)   neighbors++;
			if (neighbors > this.rule.max) return neighbors;
			
			if (this.grid[(x+1)+','+(y+1)] !== undefined) neighbors++;
			return neighbors;
		}
	};
	
}(typeof exports === 'undefined' ? this : exports));
var casper = require('casper').create();

function scrapeCategory() {
	return Array.prototype.map.call(document.querySelectorAll('#mw-pages li a'), function(a) {
		return {
			name: a.innerHTML,
			link: a.getAttribute('href')
		};
	});
}


var categories = ['http://www.conwaylife.com/wiki/Category:Methuselahs'];
var pages;
casper.start(categories[0], function() {
	pages = this.evaluate(scrapeCategory);
});
casper.then()


'use strict';

/**
 * @ngdoc module
 * @name spyGrid
 */
require('Ui').ng
	.module('spyGrid', ['spyControl', 'spyBase', 'ngAnimate', 'ngResource'])
	.run(['$templateCache', function(t) {
		var fs = require('fs');
		t.put('spyGrid/GridHead' , fs.readFileSync(__dirname + '/template/GridHead.html' , 'utf8'));
		t.put('spyGrid/GridItems', fs.readFileSync(__dirname + '/template/GridItems.html', 'utf8'));
		t.put('spyGrid/GridPage' , fs.readFileSync(__dirname + '/template/GridPage.html' , 'utf8'));
		t.put('spyGrid/GridPages', fs.readFileSync(__dirname + '/template/GridPages.html', 'utf8'));
	}]);


module.exports.events = require('./event/GridEvent');

require('./service/GridModelService');

require('./controller/GridController');

require('./directive/spyGrid');
require('./directive/spyGridHead');
require('./directive/spyGridItems');
require('./directive/spyGridPage');
require('./directive/spyGridPages');

require('./directive/spyGridRead');
require('./directive/spyGridError');
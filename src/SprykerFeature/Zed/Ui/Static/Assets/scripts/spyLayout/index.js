'use strict';

/**
 * @ngdoc module
 * @name spyLayout
 */
require('Ui').ng
	.module('spyLayout', ['spyBase'])
	.run(['$templateCache', function(t) {
		var fs = require('fs');

		t.put('spyLayout/Layer', fs.readFileSync(__dirname + '/template/Layer.html', 'utf8'));
	}]);

require('./directive/spyLayer');
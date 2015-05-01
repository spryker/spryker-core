'use strict';

/**
 * @ngdoc module
 * @name spyControl
 */
require('Ui').ng
	.module('spyControl', [])
	.run(['$templateCache', function(t) {
		var fs = require('fs');

		t.put('spyControl/ControlSlide', fs.readFileSync(__dirname + '/template/ControlSlide.html', 'utf8'));
	}]);

require('./directive/spyControlSlide');
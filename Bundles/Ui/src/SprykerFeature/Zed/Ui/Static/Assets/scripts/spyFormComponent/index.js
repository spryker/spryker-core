'use strict';

/**
 * @ngdoc module
 * @name spyFormComponent
 */
require('Ui').ng
	.module('spyFormComponent', ['spyForm'/*, 'textAngular'*/])
	.run(['$templateCache', function(t) {
//		var fs = require('fs');
//
//		t.put('spyFormComponent/InputEdit', fs.readFileSync(__dirname + '/template/InputEdit.html', 'utf8'));
	}]);


//require('./directive/spyInputEdit');
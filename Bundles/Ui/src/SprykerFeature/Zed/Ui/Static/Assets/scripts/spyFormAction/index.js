'use strict';

require('Ui').ng
	.module('spyFormAction', ['spyAction', 'spyForm'])
	.run(['$templateCache', function(t) {
		var fs = require('fs');

		t.put('spyFormAction/ActionSwitch', fs.readFileSync(__dirname + '/template/ActionSwitch.html', 'utf8'));
	}]);


require('./directive/spyActionSwitch');
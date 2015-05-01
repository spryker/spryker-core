'use strict';

require('Ui').ng
	.module('spyBase')
	.factory('BooleanService', function() {
		return function(val) {
			switch(val) {
				case true    :
				case false   : return val;
				case 'false' :
				case '0'     : return false;
				default      : return Boolean(val);
			}
		};
	});
'use strict';

require('Ui').ng
	.module('spyForm')
	.directive('spyFieldset', [function() {
		return {
			restrict : 'A',

			scope : {
				group : '=spyFieldset'
			},

			templateUrl : 'spyForm/Fieldset'
		};
	}]);
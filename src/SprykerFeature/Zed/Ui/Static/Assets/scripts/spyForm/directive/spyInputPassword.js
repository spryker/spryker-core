'use strict';

require('Ui').ng
	.module('spyForm')
	.directive('spyInputPassword', [function() {
		return {
			restrict : 'A',

			scope : {
				model   : '=spyInputPassword',
				options : '=options'
			},

			templateUrl : 'spyForm/InputPassword',

			controller : 'FieldController',

			link : function(scope, selector, attributes) {
				selector[0].classList.add('field', 'password');
			}
		};
	}]);
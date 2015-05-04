'use strict';



/**
 * Password input field directive
 * @ngdoc directive
 * @name spyInputPassword
 * @restrict A
 * @param {expression} spy-input-password The field model
 * @param {expression} options            The field options
 */
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
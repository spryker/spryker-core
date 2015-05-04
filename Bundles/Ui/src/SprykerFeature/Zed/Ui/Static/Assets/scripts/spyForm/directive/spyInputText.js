'use strict';



/**
 * Text input field directive
 * @ngdoc directive
 * @name spyInputText
 * @restrict A
 * @param {expression} model   The field model
 * @param {expression} options The field options
 */
require('Ui').ng
	.module('spyForm')
	.directive('spyInputText', [function() {
		return {
			restrict : 'A',

			scope : {
				model   : '=spyInputText',
				options : '=options'
			},

			templateUrl : 'spyForm/InputText',

			controller : 'FieldController',

			link : function(scope, selector, attributes) {
				selector[0].classList.add('field', 'text');
			}
		};
	}]);
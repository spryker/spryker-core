'use strict';

/**
 * Field Message directive
 * @ngdoc directive
 * @name spyFieldMessage
 * @restrict A
 * @param {expression} spy-field-message The message model
 */
require('Ui').ng
	.module('spyForm')
	.directive('spyFieldMessage', [function() {
		return {
			restrict : 'A',

			scope : {
				model : '=spyFieldMessage'
			},

			templateUrl : 'spyForm/FieldMessage',

			link : function(scope, selector, attributes) {
				selector[0].classList.add('messages');
			}
		};
	}]);
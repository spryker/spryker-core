'use strict';



/**
 * Fieldset directive
 * @ngdoc directive
 * @name spyFieldset
 * @restrict A
 * @param {expression} spyFields The fieldset model
 */
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
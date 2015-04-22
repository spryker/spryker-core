'use strict';

/**
 * Checkbox input field directive
 * @ngdoc directive
 * @name spyInputRadio
 * @restrict A
 * @param {expression} spy-input-radio The field model
 * @param {expression} options         The field options
 */
require('Ui').ng
	.module('spyForm')
	.directive('spyInputRadio', function() {
		return {
			restrict : 'A',

			scope : {
				model   : '=spyInputRadio',
				options : '=options'
			},

			templateUrl : 'spyForm/InputRadio',

			controller : ['$controller', '$scope', function($controller, $scope) {
				$controller('FieldController', {
					$scope : $scope
				});
			}],

			link : function(scope, selector, attributes) {
				selector[0].classList.add('field', 'radio');
			}
		};
	});
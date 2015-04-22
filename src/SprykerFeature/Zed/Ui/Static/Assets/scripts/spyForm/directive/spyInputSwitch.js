'use strict';

/**
 * Single checkbox input field directive
 * @ngdoc directive
 * @name spyInputSwitch
 * @restrict A
 * @param {expression} spy-input-switch The field model
 * @param {expression} options          The field options
 */
require('Ui').ng
	.module('spyForm')
	.directive('spyInputSwitch', [function() {
		return {
			restrict : 'A',

			scope : {
				model   : '=spyInputSwitch',
				options : '=options'
			},

			templateUrl : 'spyForm/InputSwitch',

			controller : ['$controller', '$scope', function($controller, $scope) {
				$controller('FieldController', {
					$scope : $scope
				});
			}],

			link : function(scope, selector, attributes) {
				selector[0].classList.add('field', 'switch');
			}
		};
	}]);
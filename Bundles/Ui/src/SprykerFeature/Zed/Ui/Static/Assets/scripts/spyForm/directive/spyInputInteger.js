'use strict';



/**
 * Integer input field directive
 * @ngdoc directive
 * @name spyInputInteger
 * @restrict A
 * @param {expression} spy-input-integer The field model
 * @param {expression} options           The field options
 */
require('Ui').ng
	.module('spyForm')
	.directive('spyInputInteger', [function() {
		return {
			restrict : 'A',

			scope : {
				model   : '=spyInputInteger',
				options : '=options'
			},

			templateUrl : 'spyForm/InputInteger',

			controller : ['$controller', '$scope', function($controller, $scope) {
				$controller('FieldController', {
					$scope : $scope
				});

				$scope.min = ~0x0;
				$scope.max = ~0x0 >>> 1;

				$scope.$watchCollection('options', function(now, was, scope) {
					if (now === undefined) return;

					scope.min = 'min' in now ? now.min : ~0x0;
					scope.max = 'max' in now ? now.max : ~0x0 >>> 1;
				});
			}],

			link : function(scope, selector, attributes) {
				selector[0].classList.add('field', 'integer');
			}
		};
	}]);
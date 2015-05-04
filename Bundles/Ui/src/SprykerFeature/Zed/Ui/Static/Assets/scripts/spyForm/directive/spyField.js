'use strict';



/**
 * Field directive
 * @ngdoc directive
 * @name spyField
 * @restrict A
 * @param {expression} spyField The field model
 */
require('Ui').ng
	.module('spyForm')
	.directive('spyField', [function() {
		return {
			restrict : 'A',

			scope : {
				model : '=spyField'
			},

			templateUrl : 'spyForm/Field',

			controller : ['$scope', function($scope) {

				$scope.type = 'text';

				$scope.$watch('model', function(now, was, scope) {
					if (now === undefined) return;

					if (!('type' in now.constraints)) return;

					switch (now.constraints.type) {
						case 'bool' :
							$scope.type = 'switch';
							break;

						case 'int' :
						case 'integer' :
						case 'long' :
							$scope.type = 'integer';
							break;
						
						default : $scope.type = 'text';
					}
				});
			}]
		};
	}]);
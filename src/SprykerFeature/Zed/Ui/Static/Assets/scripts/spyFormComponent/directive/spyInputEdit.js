'use strict';

require('Ui').ng
	.module('spyFormComponent')
	.directive('spyInputEdit', [function() {
		return {
			restrict : 'A',

			scope : {
				model   : '=spyInputEdit',
				options : '=options'
			},

			templateUrl : 'spyFormComponent/InputEdit',

			controller : [
				'$controller',
				'$scope',

				function($controller, $scope) {
					$controller('FieldController', {
						$scope : $scope
					});
				}
			]
		};
	}]);
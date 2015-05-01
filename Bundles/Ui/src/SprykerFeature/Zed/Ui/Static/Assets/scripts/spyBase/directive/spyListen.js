'use strict';

/**
 * @ngdoc directive
 * @name spyListen
 * @restrict E
 * @param {string}      on       The event to listen to
 * @param {expression}  do       The expression to execute
 * @param {string}     [channel] The event channel
 * @param {string}     [scope]   The name of the named referenced scope if not local
 */
require('Ui').ng
	.module('spyBase')
	.directive('spyListen', [function() {
		return {
			restrict : 'E',

			scope : false,

			controller : [
				'$controller',
				'$rootScope',
				'$scope',
				'$attrs',

				function($controller, $rootScope, $scope, $attrs) {
					$controller('EventDirectiveController', {
						$rootScope : $rootScope,
						$scope : $scope,
						$attrs : $attrs
					});
				}
			]
		};
	}]);
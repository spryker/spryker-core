'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/GridEvent');



_ng
	.module('spyGrid')
	.directive('spyGridError', [function() {
		return {
			restrict : 'A',

			scope : false,

			controller: [
				'$controller',
				'$rootScope',
				'$scope',
				'$attrs',

				function($controller, $rootScope, $scope, $attrs) {
					$attrs.on = _event.E_ERROR;

					$controller('EventDirectiveController', {
						$rootScope : $rootScope,
						$scope     : $scope,
						$attrs     : $attrs
					});


					$attrs.$observe('spyGridError', function(now) {
						$attrs.do = now;
					});
				}
			]
		};
	}]);
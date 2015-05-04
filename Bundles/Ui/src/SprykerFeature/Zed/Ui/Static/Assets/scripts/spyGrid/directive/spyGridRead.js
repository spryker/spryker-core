'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/GridEvent');



/**
 * The grid read event directive
 * @ngdoc directive
 * @name spyGridRead
 * @restrict A
 * @param {expression}  spyGridRead The grid read expression
 * @param {string}     [scope]      The name of the named referenced scope if not local
 * @param {string}     [channel]    The event channel
 */
_ng
	.module('spyGrid')
	.directive('spyGridRead', [function() {
		return {
			restrict : 'A',

			scope : false,

			controller: [
				'$controller',
				'$rootScope',
				'$scope',
				'$attrs',

				function($controller, $rootScope, $scope, $attrs) {
					$attrs.on = _event.E_READ;

					$controller('EventDirectiveController', {
						$rootScope : $rootScope,
						$scope     : $scope,
						$attrs     : $attrs
					});


					$attrs.$observe('spyGridRead', function(now) {
						$attrs.do = now;
					});
				}
			]
		};
	}]);
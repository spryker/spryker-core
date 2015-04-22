'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/FormEvent');



/**
 * @ngdoc directive
 * @name spyFormUpdate
 * @restrict A
 * @param {expression}  spyFormUpdate The form update expression
 * @param {string}     [scope]        The name of the named referenced scope if not local
 * @param {string}     [channel]      The event channel
 */
_ng
	.module('spyForm')
	.directive('spyFormUpdate', [function() {
		return {
			restrict : 'A',

			scope : false,

			controller : [
				'$controller',
				'$rootScope',
				'$scope',
				'$attrs',

				function($controller, $rootScope, $scope, $attrs) {
					$attrs.on = _event.E_UPDATE;

					$controller('EventDirectiveController', {
						$rootScope : $rootScope,
						$scope     : $scope,
						$attrs     : $attrs
					});


					$attrs.$observe('spyFormUpdate', function(now) {
						$attrs.do = now;
					});
				}
			]
		};
	}]);
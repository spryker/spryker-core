'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/FormEvent');



/**
 * @ngdoc directive
 * @name spyFormAbort
 * @restrict A
 * @param {expression}  spyFormAbort The form abort expression
 * @param {string}     [scope]       The name of the named referenced scope if not local
 * @param {string}     [channel]     The event channel
 */
_ng
	.module('spyForm')
	.directive('spyFormAbort', [function() {
		return {
			restrict : 'A',

			scope : false,

			controller : [
				'$controller',
				'$rootScope',
				'$scope',
				'$attrs',

				function($controller, $rootScope, $scope, $attrs) {
					$attrs.on = _event.E_ABORT;

					$controller('EventDirectiveController', {
						$rootScope : $rootScope,
						$scope     : $scope,
						$attrs     : $attrs
					});


					$attrs.$observe('spyFormAbort', function(now) {
						$attrs.do = now;
					});
				}
			]
		};
	}]);
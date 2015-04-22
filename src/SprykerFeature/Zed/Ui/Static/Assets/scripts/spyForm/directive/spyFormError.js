'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/FormEvent');



/**
 * @ngdoc directive
 * @name spyFormError
 * @restrict A
 * @param {expression}  spyFormError The form error expression
 * @param {string}     [scope]       The name of the named referenced scope if not local
 * @param {string}     [channel]     The event channel
 */
_ng
	.module('spyForm')
	.directive('spyFormError', [function() {
		return {
			restrict : 'A',

			scope : false,

			controller : [
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


					$attrs.$observe('spyFormError', function(now) {
						$attrs.do = now;
					});
				}
			]
		};
	}]);
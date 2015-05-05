'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/ActionEvent');



/**
 * @ngdoc directive
 * @name spyActionError
 * @restrict A
 * @param {expression}  spyActionError The action error expression
 * @param {string}     [scope]         The name of the named referenced scope if not local
 * @param {string}     [channel]       The event channel
 */
_ng
	.module('spyAction')
	.directive('spyActionError', [function() {
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


					$attrs.$observe('spyActionError', function(now) {
						$attrs.do = now;
					});
				}
			]
		};
	}]);
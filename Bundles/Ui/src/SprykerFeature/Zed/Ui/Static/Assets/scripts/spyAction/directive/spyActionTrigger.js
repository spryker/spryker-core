'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/ActionEvent');



/**
 * @ngdoc directive
 * @name spyActionTrigger
 * @restrict A
 * @param {expression}  spyActionTrigger The action trigger expression
 * @param {string}     [scope]           The name of the named referenced scope if not local
 * @param {string}     [channel]         The event channel
 */
_ng
	.module('spyAction')
	.directive('spyActionTrigger', [function() {
		return {
			restrict : 'A',

			scope : false,

			controller : [
				'$controller',
				'$rootScope',
				'$scope',
				'$attrs',

				function($controller, $rootScope, $scope, $attrs) {
					$attrs.on = _event.E_TRIGGER;

					$controller('EventDirectiveController', {
						$rootScope : $rootScope,
						$scope     : $scope,
						$attrs     : $attrs
					});


					$attrs.$observe('spyActionTrigger', function(now) {
						$attrs.do = now;
					});
				}
			]
		};
	}]);
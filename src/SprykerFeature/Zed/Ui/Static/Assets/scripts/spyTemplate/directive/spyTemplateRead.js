'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/TemplateEvent');



/**
 * @ngdoc directive
 * @name spyTemplateRead
 * @restrict A
 * @param {expression}  spyTemplateRead The template read expression
 * @param {string}     [scope]          The name of the named referenced scope if not local
 * @param {string}     [channel]        The event channel
 */
_ng
	.module('spyTemplate')
	.directive('spyTemplateRead', [function() {
		return {
			restrict : 'A',

			scope : false,

			controller : [
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


					$attrs.$observe('spyTemplateRead', function(now) {
						$attrs.do = now;
					});
				}
			]
		};
	}]);
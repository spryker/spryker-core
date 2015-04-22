'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/TemplateEvent');



/**
 * @ngdoc directive
 * @name spyTemplateError
 * @restrict A
 * @param {expression}  spyTemplateError The template error expression
 * @param {string}     [scope]           The name of the named referenced scope if not local
 * @param {string}     [channel]         The event channel
 */
_ng
	.module('spyTemplate')
	.directive('spyTemplateError', [function() {
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


					$attrs.$observe('spyTemplateError', function(now) {
						$attrs.do = now;
					});
				}
			]
		};
	}]);
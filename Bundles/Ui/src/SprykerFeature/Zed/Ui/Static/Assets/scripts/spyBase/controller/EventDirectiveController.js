'use strict';



/**
 * Generic Event Directive Controller
 * @ngdoc function
 * @name EventDirectiveController
 */
require('Ui').ng
	.module('spyBase')
	.controller('EventDirectiveController', [
		'$rootScope',
		'$scope',
		'$attrs',

		function($rootScope, $scope, $attrs) {
			var type     = $attrs.on;
			var _channel = 'channel' in $attrs ? $attrs.channel : '';
			var _name    = 'scope'  in $attrs ? $attrs.scope  : '';

			var _release = $rootScope.$on(type, function(e, context, channel) {
				if (_channel === '' && channel === undefined || _channel === channel) {
					var scope = $scope;

					if (_name !== '') {
						scope = $scope.getScopeByName(_name);

						if (scope === null) return;
					}

					if (!('do' in $attrs) || typeof $attrs.do !== 'string' || $attrs.do === '')
						console.warn('SPY - no expression in event directive');		//jshint ignore: line

					scope.$argument = context;

					scope.$eval($attrs.do);

					delete scope.$argument;
				}
			});


			$scope.$on('$destroy', function(e) {
				_release();
			});
		}
	]);
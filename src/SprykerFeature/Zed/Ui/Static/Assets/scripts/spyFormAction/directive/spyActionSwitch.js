'use strict';

require('Ui').ng
	.module('spyFormAction')
	.directive('spyActionSwitch', [function() {
		return {
			restrict : 'A',

			scope : {
				state : '=state'
			},

			templateUrl : 'spyFormAction/ActionSwitch',

			controller : [
				'$controller',
				'$scope',
				'$attrs',
				'ActionModelService',

				function($controller, $scope, $attrs, service) {
					$controller('ActionController', {
						$controller        : $controller,
						$scope             : $scope,
						$attrs             : $attrs,
						ActionModelService : service
					});


					$scope.switchModel = {
						type  : 'bool',
						value : false
					};

					$scope.switchOptions = {};


					$attrs.$observe('spyActionSwitch', function(now) {
						$attrs.spyAction = now;
					});

					$attrs.$observe('options', function(now) {
						var model = $scope.$eval(now);

						if ((model instanceof Object)) $scope.switchOptions = model;
						else $scope.switchOptions = {};
					});


					$scope.$watch('state', function(now, was, scope) {
						scope.switchModel.value = Boolean(now);
					});

					$scope.$watch('switchModel.value', function(now, was, scope) {
						if (now === scope.state) return;

						$scope
							.trigger()
							.then(function(model) {
								$scope.state = model.state;
							}, function(why) {
								console.warn('SPY - model state unknown, not updating state');		//jshint ignore:line
							});
					});
				}
			]
		};
	}]);
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
				'BooleanService',

				function($controller, $scope, $attrs, bool) {
					$controller('ActionController', {
						$scope             : $scope,
						$attrs             : $attrs
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
						scope.switchModel.value = bool(now);
					});

					$scope.$watch('switchModel.value', function(now, was, scope) {
						if (now === scope.state) return;

						$scope.switchModel.disabled = true;

						$scope
							.update({
								value : $scope.switchModel.value
							})
							.then(function(model) {
								$scope.state = model.value;
							}, function(why) {
								$scope.switchModel.value = $scope.value;

								console.warn('SPY - model state unknown, not updating state');		//jshint ignore:line
							})
							.finally(function() {
								$scope.switchModel.disabled = false;
							});
					});
				}
			]
		};
	}]);
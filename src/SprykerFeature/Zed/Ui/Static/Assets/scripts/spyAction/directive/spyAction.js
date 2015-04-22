'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/ActionEvent');



/**
 * Url based action directive
 * @ngdoc directive
 * @name spyAction
 * @restrict A
 * @param {expression} spyFormatAction The action location
 * @param {object}     options         The action options
 */
require('Ui').ng
	.module('spyAction')
	.directive('spyAction', [function(service) {
		return {
			restrict : 'A',

			scope : true,

			controller : [
				'$controller',
				'$scope',
				'$attrs',
				'ActionModelService',

				function($controller, $scope, $attrs, service) {
					$controller('ComController', {
						$scope : $scope,
						$attrs : $attrs
					});

					$scope.query = false;


					$scope.trigger = function() {
						$scope.query = true;

						return service($attrs.spyAction)
							.get()
							.$promise.then(function(model) {
								$scope.query = false;

								$scope.$emitAllChannels(_event.E_TRIGGER, model);
							}, function(why) {
								$scope.query = true;

								console.warn('SPY - action returned with errors: ' + why);	//jshint ignore:line

								$scope.$emitAllChannels(_event.E_ERROR, why);
							});
					};
				}
			],

			link : function(scope, selector, attributes) {
				var _element = selector[0];


				function _onAction(e) {
					scope.trigger();

					e.preventDefault();
					e.stopPropagation();
				}


				_element.addEventListener('click', _onAction, false);


				scope.$on('$destroy', function(e) {
					_element.removeEventListener('click', _onAction, false);
				});
			}
		};
	}]);
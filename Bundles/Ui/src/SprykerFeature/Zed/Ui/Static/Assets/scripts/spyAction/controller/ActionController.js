'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/ActionEvent');



_ng
	.module('spyAction')
	.controller('ActionController', [
		'$controller',
		'$scope',
		'$attrs',
		'$q',
		'ActionModelService',

		function($controller, $scope, $attrs, $q, service) {
			$controller('ComController', {
				$scope : $scope,
				$attrs : $attrs
			});

			$scope.query = false;


			$scope.update = function(model) {
				$scope.query = true;

				if (!(model instanceof Object)) model = {};

				return service($attrs.spyAction)
					.update(model)
					.$promise.then(function(model) {
						$scope.query = false;

						$scope.$emitAllChannels(_event.E_TRIGGER, model);

						return model;
					}, function(why) {
						$scope.query = true;

						console.warn('SPY - action returned with errors');	//jshint ignore:line

						$scope.$emitAllChannels(_event.E_ERROR, why);

						return $q.reject(why);
					});
			};
		}
	]);
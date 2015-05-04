'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/TemplateEvent');



/**
 * Basic Template Controller
 * @ngdoc function
 * @name TemplateController
 */
_ng
	.module('spyTemplate')
	.controller('TemplateController', [
		'$controller',
		'$scope',
		'$attrs',
		'$timeout',
		'TemplateModelService',
		'ArrayModelTransformService',

		function($controller, $scope, $attrs, $timeout, service, transform) {
			$controller('ComController', {
				$scope : $scope,
				$attrs : $attrs
			});


			var _request = null;


			$scope.model = null;
			$scope.field = [];


			$scope.read = function() {
				if (!('src' in $attrs) || $attrs.src === '') throw new Error('SPY - trying to access undefined datasource');

				_request = service($attrs.src).get({});

				return _request
					.$promise
					.then(function(model) {
						if (!_ng.equals(_request, model)) return new Error('SPY - request-response mismatch');

						$scope.field = model;

						$scope.$emitAllChannels(_event.E_READ, model);
					}, function(why) {
						console.warn(why);	//jshint ignore:line

						$scope.$emitAllChannels(_event.E_ERROR, why);
					});
			};


			$attrs.$observe('model', function(now) {
				var model = null;

				try {
					model = JSON.parse(now);

					if (!(model instanceof Array)) throw new Error();
				}
				catch(err) {
					console.warn('SPY - model attribute does not contain a valid model; ignored');	//jshint ignore:line

					$scope.$emitAllChannels(_event.E_ERROR, now);

					return;
				}

				$scope.field = transform(model, true, 'name', 'type', 'fields');

				$scope.$emitAllChannels(_event.E_READ, $scope.field);
			});


			$timeout(function() {
				if ($scope.field.length === 0) $scope.read();
			}, 0);
		}
	]);
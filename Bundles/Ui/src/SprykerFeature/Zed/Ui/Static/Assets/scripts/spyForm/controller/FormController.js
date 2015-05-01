'use strict';

var _ng     = require('Ui').ng;
var _event  = require('../event/FormEvent');
var _fevent = require('../event/FieldEvent');



/**
 * Basic Form Controller
 * @ngdoc function
 * @name FormController
 */
_ng
	.module('spyForm')
	.controller('FormController', [
		'$controller',
		'$scope',
		'$attrs',
		'$window',
		'$timeout',
		'$location',
		'FormModelService',
		'ArrayModelTransformService',

		function($controller, $scope, $attrs, $window, $timeout, $location, service, transform) {
			$controller('TemplateActionController', {
				$scope : $scope,
				$attrs : $attrs
			});

			$scope.model = null;
			$scope.field = [];

			$scope.query = false;

			$scope.contextWatch = null;


			$scope.read = function(param) {
				if (!('src' in $attrs) || $attrs.src === '') throw new Error('SPY - trying to access undefined datasource');

				if ($scope.query) return;

				$scope.query = true;

				$scope.model = service($attrs.src).get(param);

				return $scope.model
					.$promise
					.then(function(model) {
						$scope.query = false;

						$scope.$emitAllChannels(_event.E_READ, model.fields);
					}, function(why) {
						console.warn(why);	//jshint ignore:line

						$scope.$emitAllChannels(_event.E_ERROR, why);
					});
			};

			$scope.test = function() {
				if (!('src' in $attrs) || $attrs.src === '') throw new Error('SPY - trying to access undefined datasource');

				if ($scope.query) return;

				$scope.query = true;

				var promise;

				if ($scope.model === null) {
					$scope.model = service($attrs.src).test({ fields : $scope.field });

					promise  = $scope.model.$promise;
				}
				else promise = $scope.model.$test();

				return promise
					.then(function(model) {
						$scope.query = false;

						$scope.$emitAllChannels(_event.E_READ, model.fields);
					}, function(why) {
						console.warn(why);	//jshint ignore:line

						$scope.$emitAllChannels(_event.E_ERROR, why);
					});
			};

			$scope.update = function() {
				if (!('src' in $attrs) || $attrs.src === '') throw new Error('SPY - trying to access undefined datasource');

				if ($scope.query) return;

				$scope.query = true;

				var promise;

				if ($scope.model === null) {
					$scope.model = service($attrs.src).save({ fields : $scope.field });

					promise = $scope.model.$promise;
				}
				else promise = $scope.model.$save();

				return promise
					.then(function(model) {
						$scope.query = false;

						$scope.$emitAllChannels(model.state === 'success' ? _event.E_UPDATE : _event.E_READ, model.fields);
					}, function(why) {
						console.warn(why);	//jshint ignore:line

						$scope.$emitAllChannels(_event.E_ERROR, why);
					});
			};


			$scope.abort = function() {
				if ($scope.query) return;

				$scope.$emitAllChannels(_event.E_ABORT, null);
			};


			$attrs.$observe('model', function(now) {
				var model = null;

				try {
					model = JSON.parse(now);

					if (!(model instanceof Object) || !('fields' in model)) throw new Error();
				}
				catch(err) {
					console.warn('SPY - model attribute does not contain a valid model; ignored');		//jshint ignore:line

					$scope.$emitAllChannels(_event.E_ERROR, now);

					return;
				}

				$scope.model = null;
				$scope.field = transform(model.fields, true, 'name', 'type', 'fields');

				$scope.$emitAllChannels(_event.E_READ, $scope.field);
			});


			$scope.$watch('model.fields', function(now, was, scope) {
				if (now !== undefined) $scope.field = now;
			});


			$scope.$on(_fevent.E_READ, function(e, model) {
				$scope
					.test()
					.then(function() {
						$scope.$broadcast(_event.E_NOTIFY_READ, model);
					}, function() {
						$scope.$broadcast(_event.E_NOTIFY_ERROR, model);
					});
			});

			$scope.$on(_fevent.E_SAVE, function(e, model) {
				$scope
					.update()
					.then(function() {
						$scope.$broadcast(_event.E_NOTIFY_SAVE, model);
					}, function() {
						$scope.$broadcast(_event.E_NOTIFY_ERROR, model);
					});
			});


			$timeout(function() {
				if ($scope.field.length === 0) $scope.read();
			}, 0);
		}
	]);
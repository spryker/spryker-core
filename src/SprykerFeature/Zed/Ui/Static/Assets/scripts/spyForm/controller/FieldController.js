'use strict';

var _ng     = require('Ui').ng;
var _event  = require('../event/FieldEvent');
var _fevent = require('../event/FormEvent');


var _id = 0;

var _prefixField = 'spy-form';



/**
 * Basic Field Controller
 * @ngdoc function
 * @name FieldController
 */
_ng
	.module('spyForm')
	.controller('FieldController', [
		'$scope',
		'$timeout',

		function($scope, $timeout) {

			$scope.label       = "";
			$scope.placeholder = "";

			$scope.focus    = false;
			$scope.read     = false;
			$scope.update   = false;

			$scope.updated  = false;
			$scope.disabled = false;

			$scope.unique   = _prefixField + (++_id).toString();


			$scope.$watch('options', function(now, was, scope) {
				if (now === undefined) return;

				if ('label' in now) scope.label = now.label;
				if ('placeholder' in now) scope.placeholder = now.placeholder;

				scope.disabled = 'disabled' in now && now.disabled === true;

				if ('reload' in now) scope.read = Boolean(now.reload);
				else if ('model' in scope && scope.model instanceof Object) scope.read = scope.model.refresh;
				else scope.reload = false;

				if ('update' in now) scope.update = Boolean(now.update);
				else if ('model' in scope && scope.model instanceof Object) scope.update = scope.model.update;
				else scope.update = false;
			});

			$scope.$watchCollection('model', function(now, was, scope) {
				if (now === undefined) return;

				var opts = 'options' in scope &&  scope.options !== undefined ? scope.options : {};

				if (!('label' in opts) && now.label !== null) scope.label = now.label;

				if ('reload' in opts) scope.read = Boolean(opts.reload);
				else scope.read = now.refresh;

				if ('update' in opts) scope.update = Boolean(opts.update);
				else scope.update = now.update;
			});

			$scope.$watch('model.value', function(now, was, scope) {
				if (now === was || was === undefined) return;

				if (scope.save) scope
					.$emit(_event.E_SAVE, scope.model);
				else if (scope.read) scope.$emit(_event.E_READ, scope.model);
			});


			$scope.$on(_fevent.E_NOTIFY_UPDATE, function(e, model) {
				$scope.updated = true;

				$timeout(function() {
					$scope.updated = false;
				}, 1000);
			});
		}
	]);
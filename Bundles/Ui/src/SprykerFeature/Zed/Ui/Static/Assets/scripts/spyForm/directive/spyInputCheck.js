'use strict';

var _ng = require('Ui').ng;



/**
 * Checkbox input field directive
 * @ngdoc directive
 * @name spyInputCheck
 * @restrict A
 * @param {expression} spy-input-check The field model
 * @param {expression} options         The field options
 */
_ng
	.module('spyForm')
	.directive('spyInputCheck', function() {
		return {
			restrict : 'A',

			scope : {
				model   : '=spyInputCheck',
				options : '=options'
			},

			templateUrl : 'spyForm/InputCheck',

			controller : ['$controller', '$scope', function($controller, $scope) {
				$controller('FieldController', {
					$scope : $scope
				});


				$scope.value = [];


				$scope.$watch('model.value', function(now, was, scope) {
					var _model = $scope.model;
					var _value = $scope.value;

					_model.accepts.forEach(function(item, index, source) {
						_value[index] = _model.value.indexOf(item.value) !== -1;
					});
				});

				$scope.$watchCollection('value', function(now, was, scope) {
					var _model = $scope.model;
					var _value = $scope.value;

					_model.value = [];

					_model.accepts.forEach(function(item, index, source) {
						if (_value[index]) _model.value.push(item.value);
					});
				});
			}],

			link : function(scope, selector, attributes) {
				selector[0].classList.add('field', 'check');
			}
		};
	});
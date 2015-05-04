'use strict';



/**
 * TextArea input field directive
 * @ngdoc directive
 * @name spyInputArea
 * @restrict A
 * @param {expression} spy-input-area The field model
 * @param {expression} options        The field options
 */
require('Ui').ng
	.module('spyForm')
	.directive('spyInputArea', [
		'$window',
		'$timeout',

		function($window, $timeout) {
			return {
				restrict : 'A',

				scope : {
					model   : '=spyInputArea',
					options : '=options'
				},

				templateUrl : 'spyForm/InputArea',

				controller : ['$controller', '$scope', function($controller, $scope) {
					$controller('FieldController', {
						$scope : $scope
					});
				}],

				link : function(scope, selector, attributes) {
					var _element = selector[0];
					var _input  = _element.querySelector('textarea');

					_element.classList.add('field', 'area');


					scope.resize = function() {
						$timeout(function() {
							_input.style.height = "0";
							_input.style.height = _input.scrollHeight + "px";
						}, 0);
					};


					scope.$watch('model', function(now, was, scope) {
						scope.resize();
					});


					_input.addEventListener('keypress', scope.resize, false);
					$window.addEventListener('resize' , scope.resize, false);


					scope.$on('$destroy', function(e) {
						_input.removeEventListener('keypress', scope.resize, false);
						$window.removeEventListener('resize' , scope.resize, false);
					});
				}
			};
		}
	]);
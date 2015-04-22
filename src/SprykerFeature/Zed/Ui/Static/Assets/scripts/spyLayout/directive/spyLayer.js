'use strict';

var _ng = require('Ui').ng;



/**
 * Layer directive
 * @ngdoc directive
 * @name spyLayer
 * @restrict E
 * @param {expression} spy-layer The trigger expression
 */
_ng
	.module('spyLayout')
	.directive('spyLayer', [function() {
		return {
			restrict : 'E',

			scope : true,

			transclude : true,

			templateUrl : 'spyLayout/Layer',

			controller : ['$controller', '$scope', '$attrs', function($controller, $scope, $attrs) {
				$controller('ComController', {
					$scope : $scope,
					$attrs : $attrs
				});


				$scope.open = false;


				$scope.openLayer = function() {
					$scope.open = true;
				};

				$scope.closeLayer = function() {
					$scope.open = false;
				};

				$scope.triggerLayer = function() {
					$scope.open = !$scope.open;
				};
			}],

			link : function(scope, selector, attributes, controller, transclude) {
				var _element = selector[0];
				var _content = _element.querySelector('div.content');

				transclude(scope, function(clone, scope) {
					_ng.element(_content).append(clone);
				});

				scope.$watch('open', function(now, was, scope, controller, transclude) {
					if (now) _element.classList.add('active');
					else _element.classList.remove('active');
				});
			}
		};
	}]);
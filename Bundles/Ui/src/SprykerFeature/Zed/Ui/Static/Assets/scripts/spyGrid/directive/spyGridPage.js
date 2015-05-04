'use strict';



/**
 * Grid pagination page selection control
 * @ngdoc directive
 * @name SpyGridPage
 * @restrict A
 * @param {expression} spy-grid-page The pagination page model
 * @param {string}     label         The control label
 */
require('Ui').ng
	.module('spyGrid')
	.directive('spyGridPage', [function() {
		return {
			restrict : 'A',

			scope : {
				model : '=spyGridPage',
				label : '=label'
			},

			templateUrl : 'spyGrid/GridPage',

			controller : ['$scope', function($scope) {

				$scope.strings = {
					part0 : "",
					part1 : "",
					partN : ""
				};

				$scope.$watch('label', function(now, was, scope) {
					if (now === undefined) return;

					var index = now.indexOf('%n');

					$scope.strings.part0 = now.substring(0, index);

					var part = now.substring(index + 2);
					index = part.indexOf('%m');

					$scope.strings.part1 = part.substring(0, index);
					$scope.strings.partN = part.substring(index + 2);
				});


				$scope.prev = function() {
					$scope.model.now = Math.max($scope.model.now - 1, $scope.model.min);
				};

				$scope.next = function() {
					$scope.model.now = Math.min($scope.model.now + 1, $scope.model.max);
				};
			}],

			link : function(scope, selector, attributes) {
				var _label = Array.prototype.slice.call(selector[0].querySelectorAll('label'), 0);
				var _click = scope.$broadcast.bind(scope, 'control.pull.focus', {});


				_label.forEach(function(item, index, source) {
					item.addEventListener('click', _click, false);
				});


				scope.$on('$destroy', function(e) {
					_label.forEach(function(item, index, source) {
						item.removeEventListener('click', _click, false);
					});
				});
			}
		};
	}]);
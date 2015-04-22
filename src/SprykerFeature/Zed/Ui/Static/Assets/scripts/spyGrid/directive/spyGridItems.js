'use strict';

/**
 * Grid pagination items per page control
 * @ngdoc directive
 * @name spyGridItems
 * @restrict A
 * @param {expression} spy-grid-items The pagination items model
 * @param {string}     label          The control label
 */
require('Ui').ng
	.module('spyGrid')
	.directive('spyGridItems', [function() {
		return {
			restrict : 'A',

			scope : {
				model : '=spyGridItems',
				label : '=label'
			},

			templateUrl : 'spyGrid/GridItems',

			controller : ['$scope', function($scope) {
				$scope.strings = {
					part0 : "",
					partN : ""
				};


				$scope.$watch('label', function(now, was, scope) {
					if (now === undefined) return;

					var index = now.indexOf('%n');

					$scope.strings.part0 = now.substring(0, index);
					$scope.strings.partN = now.substring(index + 2);
				});
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
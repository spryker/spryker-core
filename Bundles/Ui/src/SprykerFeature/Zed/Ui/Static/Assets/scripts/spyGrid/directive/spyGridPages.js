'use strict';



/**
 * Grid pagination control
 * @ngdoc directive
 * @name spyGridPages
 * @restrict A
 * @param {expression} spy-grid-pages The pagination model
 * @param {object}     labels         The pagination control labels
 */
require('Ui').ng
	.module('spyGrid')
	.directive('spyGridPages', [function() {
		return {
			restrict : 'A',

			scope : {
				'model' : '=spyGridPages',
				'label' : '=labels'
			},

			templateUrl : 'spyGrid/GridPages',

			controller : ['$scope', function($scope) {
				$scope.showItem   = true;
				$scope.showPage   = true;
				$scope.beforeGrid = false;

				$scope.$watchCollection('model', function(now, was, scope) {
					if (scope.beforeGrid) scope.showItem = now.page.max > 1;
					else scope.showItem = now.page.max > 1 || now.items.num > 15;

					scope.showPage = now.page.max > 1;
				});
			}],

			link : function(scope, selector, attributes) {
				var element = selector[0];
				var nodes   = element.parentNode.querySelectorAll('[spy-grid-pages], table');

				scope.beforeGrid = false;

				Array.prototype
					.slice.call(nodes, 0)
					.some(function(item, index, source) {
						if (item === element) {
							scope.beforeGrid = true;

							return true;
						}

						var name = item.nodeName.toLowerCase();

						if (name === 'table') return true;
					});
			}
		};
	}]);
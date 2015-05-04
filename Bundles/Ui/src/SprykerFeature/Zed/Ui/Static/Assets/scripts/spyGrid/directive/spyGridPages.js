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
				$scope.showItem = true;
				$scope.showPage = true;

				$scope.$watchCollection('model', function(now, was, scope) {
					$scope.showItem = now.pages.max > 1 && now.rows.length > 15;
					$scope.showPage = now.pages.max > 1;
				});
			}]
		};
	}]);
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

			templateUrl : 'spyGrid/GridPages'
		};
	}]);
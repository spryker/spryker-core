'use strict';



/**
 * @ngdoc directive
 * @name spyGrid
 * @restrict E
 */
require('Ui').ng
	.module('spyGrid')
	.directive('spyGrid', [function() {
		return {
			restrict : 'E',

			scope : true,

			transclude : true,

			controller : 'GridController',

			link : function(scope, selector, attributes, ctrl, transclude) {
				transclude(scope, function(clone, scope) {
					selector.append(clone);
				});
			}
		};
	}]);
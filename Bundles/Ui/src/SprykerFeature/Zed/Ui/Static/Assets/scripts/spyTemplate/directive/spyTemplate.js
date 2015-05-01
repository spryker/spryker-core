'use strict';

require('Ui').ng
	.module('spyTemplate')
	.directive('spyTemplate', [function() {
		return {
			restrict : 'E',

			transclude : true,

			scope : true,

			controller : 'TemplateController',

			link : function(scope, selector, attributes, ctrl, transclude) {
				transclude(scope, function(clone, scope) {
					selector.append(clone);
				});
			}
		};
	}]);
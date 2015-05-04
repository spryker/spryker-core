'use strict';

var _ng = require('Ui').ng;



/**
 * Form directive
 * @ngdoc directive
 * @name spyForm
 * @restrict E
 * @param src The url of the datasource
 */
_ng
	.module('spyForm')
	.directive('spyForm', [function() {
		return {
			restrict : 'E',

			scope : true,

			transclude : true,

			templateUrl : 'spyForm/Form',

			controller : 'FormController',

			link : function(scope, selector, attributes, ctrl, transclude) {
				transclude(scope, function(clone, scope) {
					_ng.element(selector[0].querySelector('form')).append(clone);
				});
			}
		};
	}]);
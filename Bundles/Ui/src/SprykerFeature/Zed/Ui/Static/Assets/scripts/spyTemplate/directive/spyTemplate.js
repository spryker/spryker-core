'use strict';



/**
 * The template directive
 * @ngdoc directive
 * @name spyTemplate
 * @restrict E
 * @param {string}  src      The datasource url
 * @param {object}  model    The initial template model
 * @param {string} [name]    The unique scope name
 * @param {string} [channel] Comma separated list of channels
 */
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
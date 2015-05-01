'use strict';



/**
 * Url based action directive
 * @ngdoc directive
 * @name spyAction
 * @restrict A
 * @param {expression} spyFormatAction The action location
 * @param {object}     options         The action options
 */
require('Ui').ng
	.module('spyAction')
	.directive('spyAction', [function(service) {
		return {
			restrict : 'A',

			scope : true,

			controller : 'ActionController',

			link : function(scope, selector, attributes) {
				var _element = selector[0];


				function _onAction(e) {
					scope.trigger();

					e.preventDefault();
					e.stopPropagation();
				}


				_element.addEventListener('click', _onAction, false);


				scope.$on('$destroy', function(e) {
					_element.removeEventListener('click', _onAction, false);
				});
			}
		};
	}]);
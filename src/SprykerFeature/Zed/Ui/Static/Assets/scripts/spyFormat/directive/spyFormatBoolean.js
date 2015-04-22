'use strict';

/**
 * Boolean formater directive
 * @ngdoc directive
 * @name spyFormatBoolean
 * @restrict E
 * @param {expression} content The expression to evaluate
 */
require('Ui').ng
	.module('spyFormat')
	.directive('spyFormatBoolean', [function() {
		return {
			restrict : 'E',

			scope : {
				content : '=content'
			} ,

			link : function(scope, selector, attributes) {
				var _element = selector[0];

				_element.classList.add('spy-format', 'boolean');

				scope.$watch('content', function(now, was, scope) {
					var val = Boolean(now);

					switch(typeof now) {
						case 'string' :
							if (now === '0' || now === 'false') val = false;

							break;
					}

					if (val) _element.classList.add('true');
					else _element.classList.remove('true');
				});
			}
		};
	}]);
'use strict';



/**
 * Generic template action controller
 * @ngdoc function
 * @name TemplateActionController
 */
require('Ui').ng
	.module('spyBase')
	.controller('TemplateActionController', [
		'$controller',
		'$scope',
		'$attrs',

		function($controller, $scope, $attrs) {
			$controller('ComController', {
				$scope : $scope,
				$attrs : $attrs
			});


			$scope.openLocation = function(url, target) {
				if (target === undefined) target = '_self';

				if (typeof url    !== 'string' || url    === "") throw new TypeError('SPY - invalid url');
				if (typeof target !== 'string' || target === "") throw new TypeError('SPY - invalid target window');

				window.open(url, target);
			};
		}
	]);
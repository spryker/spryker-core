'use strict';



require('Ui').ng
	.module('spyBase')
	.config([
		'$interpolateProvider',

		function($interpolateProvider) {
			$interpolateProvider
				.startSymbol('{[{')
				.endSymbol('}]}');
		}
	]);
'use strict';

/**
 * @ngdoc module
 * @name spyBase
 */
require('Ui').ng
	.module('spyBase', [])
	.config([
		'$httpProvider',

		function($httpProvider) {
			$httpProvider.interceptors.push('errorInterceptor');
			$httpProvider.interceptors.push('progressInterceptor');
			$httpProvider.interceptors.push('redirectInterceptor');
		}
	]);



require('./config/progressInterceptor');
require('./config/interpolateProvider');
require('./config/redirectInterceptor');
require('./config/errorInterceptor');

require('./service/BooleanService');
require('./service/JSONModelDenormalizeService');
require('./service/ArrayModelTransformService');

require('./controller/ComController');
require('./controller/TemplateActionController');
require('./controller/EventDirectiveController');

require('./directive/spyApp');
require('./directive/spyListen');
'use strict';



require('Ui').ng
	.module('spyBase')
	.factory('progressInterceptor', ['$window', function($window) {
		return {
			request : function(request) {
				$window.dispatchEvent(new Event('query-add'));

				return request;
			},

			response : function(response) {
				$window.dispatchEvent(new Event('query-resolve'));

				return response;
			},

			responseError : function(response) {
				$window.dispatchEvent(new Event('query-resolve'));

				return response;
			}
		};
	}]);
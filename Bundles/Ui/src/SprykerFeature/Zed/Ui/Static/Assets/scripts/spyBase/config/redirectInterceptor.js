'use strict';



require('Ui').ng
	.module('spyBase')
	.factory('redirectInterceptor', [function() {
		return {
			'response' : function(response) {
				var headers = response.headers();

				if ('spy-location' in headers) location.href = headers['spy-location'];

				return response;
			}
		};
	}]);
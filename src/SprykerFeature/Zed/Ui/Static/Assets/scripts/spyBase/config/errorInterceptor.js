'use strict';

require('Ui').ng
	.module('spyBase')
	.factory('errorInterceptor', [function() {
		return {
			response : function(response) {
				return response;
			},

			responseError : function(response) {
				if (response.status >= 500) {
					var body = document.querySelector('body');

					var f = document.createElement('iframe');
					f.className = 'spy-response-error';
					body.appendChild(f);

					setTimeout(function (f, r) {
						f.contentWindow.document.documentElement.innerHTML = r.data;
					}, 200, f, response);
				}

				return response;
			}
		};
	}]);
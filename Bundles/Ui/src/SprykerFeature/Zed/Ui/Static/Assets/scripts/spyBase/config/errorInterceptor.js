'use strict';



require('Ui').ng
	.module('spyBase')
	.factory('errorInterceptor', [
		'$q',
		'ErrorStoreService',

		function($q, errorStore) {
			return {
				response : function(response) {
					return response;
				},

				responseError : function(response) {
					if (response.status >= 500 && errorStore.has()) {

						var error = errorStore.pop();

						var body = document.querySelector('body');

						var f = document.createElement('iframe');
						f.className = 'spy-response-error';
						body.appendChild(f);

						setTimeout(function (f, r) {
							f.contentWindow.document.documentElement.innerHTML = r;
						}, 200, f, error);

						return $q.reject(response);
					}

					return response;
				}
			};
		}
	]);
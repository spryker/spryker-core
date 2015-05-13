'use strict';



/**
 * Basic GridModel service
 * @ngdoc service
 * @name GridModelService
 * @kind function
 * @param {string} url The GridModel source url
 * @returns {$resource}
 */
require('Ui').ng
	.module('spyGrid')
	.factory('GridModelService', [
		'$resource',
		'ErrorStoreService',
		'JSONModelDenormalizeService',

		function($resource, errorStore, denormalizeResponse) {

			function _transformResponse(model, headers) {
				if (model === null) {
					console.warn('SPY - Grid model cannot be resolved');	//jshint ignore:line

					return {};
				}

				return model;
			}


			return function(url) {
				return $resource(url, {}, {
					query : {
						method  : 'get',
						isArray : false,
						transformResponse : [
							errorStore.intercept,
							denormalizeResponse,
							_transformResponse
						]
					}
				});
			};
		}
	]);
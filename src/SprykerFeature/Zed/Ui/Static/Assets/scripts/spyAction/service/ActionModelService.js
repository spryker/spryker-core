'use strict';



/**
 * Basic ActionModel service
 * @ngdoc service
 * @name ActionModelService
 * @kind function
 * @param {string} url The ActionModel source url
 * @returns {$resource}
 */
require('Ui').ng
	.module('spyAction')
	.factory('ActionModelService', [
		'$resource',
		'JSONModelDenormalizeService',

		function($resource, denormalizeResponse) {

			return function(url) {
				return $resource(url, {}, {
					get : {
						method : 'get',
						isArray : false,
						transformResponse : denormalizeResponse
					}
				});
			};
		}
	]);
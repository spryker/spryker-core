'use strict';



/**
 * Basic TemplateModel service
 * @ngdoc service
 * @name TemplateModelService
 * @param {string} url The TemplateModel source url
 * @returns {$resource}
 */
require('Ui').ng
	.module('spyTemplate')
	.factory('TemplateModelService', [
		'$resource',
		'JSONModelDenormalizeService',
		'ArrayModelTransformService',

		function($resource, denormalizeResponse, transform) {
			return function(url) {

				function _transformResponse(model, headers) {
					try {
						return transform(model, true, 'name', 'type', 'children');
					}
					catch(err) {
						console.warn('SPY - template model cannot be resolved');	//jshint ignore:line

						return [];
					}
				}

				return $resource(url, {}, {
					get : {
						method : 'get',
						isArray : true,
						transformResponse : [
							denormalizeResponse,
							_transformResponse
						]
					}
				});
			};
		}
	]);

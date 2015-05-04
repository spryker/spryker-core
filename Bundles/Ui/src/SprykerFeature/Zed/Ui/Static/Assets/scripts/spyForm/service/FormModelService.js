'use strict';



/**
 * Basic FormModel service
 * @ngdoc service
 * @name FormModelService
 * @param {string} url The FormModel source url
 * @returns {$resource}
 */
require('Ui').ng
	.module('spyForm')
	.factory('FormModelService', [
		'$resource',
		'JSONModelDenormalizeService',
		'ArrayModelTransformService',

		function($resource, denormalizeResponse, transform) {

			function _transformRequest(model, headers) {
				var _res = {};

				function buildQuery(fields) {
					fields.forEach(function(item, index, source) {
						if (item.name in _res) throw new Error('SPY - Duplicate name in Form Model');

						if (item.type === 'group') buildQuery(item.fields);
						else _res[item.name] = item.value;
					});
				}

				buildQuery(model.fields);

				return JSON.stringify(_res);
			}

			function _transformResponse(model, headers) {
				try {
					transform(model.fields, true, 'name', 'type', 'fields');

					return model;
				}
				catch(err) {
					console.warn('SPY - Form Model cannot be resolved');	//jshint ignore:line

					return {};
				}
			}


			return function(url) {
				return $resource(url, {}, {
					get : {
						method : 'post',
						isArray : false,
						transformResponse : [
							denormalizeResponse,
							_transformResponse
						]
					},
					save : {
						method : 'put',
						isArray : false,
						transformRequest  : _transformRequest,
						transformResponse : [
							denormalizeResponse,
							_transformResponse
						]
					},
					test : {
						method : 'post',
						isArray : false,
						transformRequest  : _transformRequest,
						transformResponse : [
							denormalizeResponse,
							_transformResponse
						]
					}
				});
			};
		}
	]);
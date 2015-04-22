'use strict';

/**
 * JSON Response normalization service
 * @ngdoc service
 * @name JSONModelNormalizeService
 * @param {string}   data    The unfiltered server response
 * @param {function} headers A callback returning the response headers
 * @returns {Object|Array|null}
 */
require('Ui').ng
	.module('spyBase')
	.factory('JSONModelDenormalizeService', [function() {
		return function(data, headers) {
			var model;

			try {
				model = JSON.parse(data);

				if (!('content' in model)) throw new Error();
			}
			catch(err) {
				console.warn('SPY - server returned malformed model');	//jshint ignore:line

				return null;
			}

			return model.content;
		};
	}]);
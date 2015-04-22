'use strict';

/**
 * Generic array model transformation service
 * @ngdoc service
 * @name ArrayModelTransformService
 * @param {Array}    model              The model
 * @param {boolean} [deep=true]         <code>true</code> if the transform should also be applied to children, <code>false</code> otherwise
 * @param {string}  [id='name']         The identity property name
 * @param {string}  [type='type']       The type property name
 * @param {string}  [children='fields'] The child collection property name
 * @returns {Array}
 */
require('Ui').ng
	.module('spyBase')
	.factory('ArrayModelTransformService', [function() {
		return function transformProps(model, deep, id, type, children) {
			if (deep     === undefined) deep     = true;
			if (id       === undefined) id       = 'name';
			if (type     === undefined) type     = 'type';
			if (children === undefined) children = 'fields';

			if (
				!(model instanceof Array) ||
				typeof deep !== 'boolean' ||
				typeof id !== 'string' || id === "" ||
				typeof type !== 'string' || type === "" ||
				typeof children !== 'string' || children === ""
			) throw new TypeError();

			model.forEach(function(item, index, source) {
				if (!(item instanceof Object) || !(id in item) || typeof item[id] !== 'string' || parseInt(item[id]).toString() === item[id]) throw new Error('SPY - invalid key in model: ' + id);
				if (item[id] in source) throw new Error('SPY - duplicate key in model: ' + item[id]);

				source[item[id]] = item;

				if (deep && type in item && item[type] === 'group') {
					if (!(children in item) || !(item[children] instanceof Array)) throw new Error('SPY - invalid structure in model:');

					transformProps(item[children], true, id, type, children);
				}
			});

			return model;
		};
	}]);
'use strict';

/**
 * Alternative to ng-app with support for multiple angular instances in a single page
 * @ngdoc directive
 * @name spyApp
 * @restrict A
 * @param {string} Comma separated list of root modules
 */
var _ng = require('Ui').ng;

_ng.element(document).ready(function() {
	try {
		var node = Array.prototype.slice.call(document.querySelectorAll('[spy-app],[ng-app]'));

		node.forEach(function (item, index, source) {
			if (item.hasAttribute('ng-app')) throw new Error("SPY - Conflicting use of ng-app - aborting spy-app bootstrap");

			var child = item.querySelector('[spy-app]');

			if (child === null) return;

			if (child.hasAttribute('spy-app')) throw new Error("SPY - Nested use of spy-app - aborting spy-app bootstrap");
		});

		node.forEach(function (item, index, source) {
			var modules = item.getAttribute('spy-app').replace(/[^A-Z0-9a-z\,\-_]/g, "").split(",");

			_ng.bootstrap(item, modules);
		});
	}
	catch (err) {
		console.warn(err.message);	//jshint ignore:line
	}
});
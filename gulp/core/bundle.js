'use strict';

var _q = require('q');

var _fs   = require('fs');
var _path = require('path');



var _dirBundle = 'src/SprykerFeature/Zed/%s/Static';


var _bundlePaths = [];
var _bundles     = null;



/**
 * Returns a CamelCase transformed representation of camel-case
 * @param {String} name The snake-cased input
 * @returns {String}
 */
function _snakeToCamel(name) {
	return name
		.replace(/^[a-z]/, function(match) {
			return match.toUpperCase();
		})
		.replace(/\-[a-z]/g, function(match) {
			return match[1].toUpperCase();
		});
}


/**
 * Returns a promise resolving to an <code>Array</code> of current <em>Bundle</em> names
 * @param {String[]} directories The bundle base directories
 * @returns {_q}
 */
function _buildBundles(directories) {
	if (!(directories instanceof Array)) return _q(new TypeError());

	var p = _q([]), index = 0;

	function resolve(bundles) {
		var dir = directories[index];

		return _q
			.nfcall(_fs.stat, dir)
			.then(function(stat) {
				if (!stat.isDirectory()) return _q(new Error());

				return _q.nfcall(_fs.readdir, dir);
			})
			.then(function(files) {
				return _q
					.all(files.map(function(item, index, source) {
						var name = _snakeToCamel(item);
						var path = _path.join(dir, item, _dirBundle.replace('%s', name));

						return _q
							.nfcall(_fs.stat, path)
							.then(function(stat) {
								if (stat.isDirectory()) bundles.push({
									name : name,
									path : path
								});
							}, function(why) {
								return _q(true);
							});
					}))
			})
			.then(function(all) {
				index += 1;

				return _q(bundles);
			}, function(why) {
				index += 1;

				return _q(bundles);
			});
	}

	for (var i = 0; i < directories.length; i += 1) p = p.then(resolve);

	return p;
}



/**
 * Returns a promise resolving to an <code>Array</code> of <em>Bundle</em> names
 * @param   {String[]} dir The bundle base directories
 * @returns {_q}
 */
module.exports = function _getBundles(directories) {
	if (directories.length !== _bundlePaths.length) var build = true;
	else build = directories.some(function(item, index, source) {
		return item !== _bundlePaths[index];
	});

	if (build) {
		_bundles     = _buildBundles(directories);
		_bundlePaths = directories;
	}

	var d = _q.defer();

	_bundles.then(d.resolve.bind(d));

	return d.promise;
};
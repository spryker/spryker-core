'use strict';

var _q = require('q');

var _fs    = require('fs');
var _path  = require('path');
var _child = require('child_process');

var _getBundles   = require('./bundle');
var _resolveTasks = require('./resolve');



/**
 * Asynchronously installs the <em>bundle</em> module dependencies
 * @param {String[]}  base The base paths
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after all bundle module dependencies are installed
 * @returns {_q}
 */
exports.installModules = function(base, dev, done) {
	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			console.log(_path.join(bundle.base, 'package.json'));

			return _q
				.nfcall(_fs.stat, _path.join(bundle.base, 'package.json'))
				.then(function(stat) {
					return _q.Promise(function(resolve, reject, notify) {
						_child.spawn('npm', ['run', 'spy-install'], {
							cwd   : bundle.base,
							stdio : 'inherit'
						})
							.on('close', resolve);
					});
				}, function(why) {
					return _q(true);
				})
				.then(null, function(why) {
					console.log(why);
				});
		});

		return _resolveTasks(tasks, done);
	});
};
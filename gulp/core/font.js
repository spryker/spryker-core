'use strict';

var _q    = require('q');
var _gulp = require('gulp');

var _getBundles   = require('./bundle');
var _getPaths     = require('./paths');
var _resolveTasks = require('./resolve');



/**
 * Asynchronously cleans the <em>bundle</em> font destination folders
 * @param {String[]}  base The base paths
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after all directories are cleaned
 * @returns {_q}
 */
exports.cleanBundledFonts = function(base, dev, done) {
	var _del = require('del');

	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var target = _getPaths(bundle.path, 'dst', 'fnt', '');

			return _q.Promise(function(resolve, reject, notify) {
				_del(target, {
					force : true
				}, resolve);
			});
		});

		return _resolveTasks(tasks, done);
	});
};


/**
 * Asynchronously copies the fonts of each <em>bundle</em>
 * @param {String[]}  base The base paths
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] called after copying completes
 * @returns {_q}
 */
exports.copyBundledFonts = function(base, dev, done) {
	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var source = _getPaths(bundle.path, 'src', 'fnt', '**/*.{otf,ttf,woff,svg,eot}');
			var target = _getPaths(bundle.path, 'dst', 'fnt', '');

			return _q.Promise(function(resolve, reject, notify) {
				_gulp
					.src(source)
					.pipe(_gulp.dest(target))
					.on('finish', resolve)
					.on('error', reject);
			});
		});

		return _resolveTasks(tasks, done);
	});
};
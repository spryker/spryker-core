'use strict';

var _q    = require('q');
var _gulp = require('gulp');

var _path = require('path');


var _getBundles   = require('./bundle');
var _getPaths     = require('./paths');
var _resolveTasks = require('./resolve');



/**
 * Asynchronously cleans the <em>bundle</em> css destination folders
 * @param {String[]}  base The base paths
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after all directories are cleaned
 * @returns {_q}
 */
exports.cleanBundledCss = function(base, dev, done) {
	var _del = require('del');

	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var target = _getPaths(bundle.path, 'dst', 'css', '');

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
 * Asynchronously creates a <code>bundle.css</code> file for each <em>bundle</em>
 * @param {String[]}  base The base paths
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after all files are created
 * @returns {_q}
 */
exports.createBundledCss = function(base, dev, done) {
	var _concat = require('gulp-concat');
	var _sass   = require('gulp-sass');
	var _maps   = require('gulp-sourcemaps');

	return _getBundles(base).then(function(bundles) {
		var global = _path.join(process.cwd(), 'src/SprykerFeature/Zed/Ui/Static/Assets/styles/_shared.scss');

		var tasks = bundles.map(function(bundle) {
			var source = _getPaths(bundle.path, 'src', 'css', '**/*.{scss,css}');
			var target = _getPaths(bundle.path, 'dst', 'css', '');

			return _q.Promise(function(resolve, reject, notify) {
				var stream = _gulp.src(source);

				if (dev) stream = stream.pipe(_maps.init());

				stream = stream
					.on('data', function(file)  {
						file.contents = Buffer.concat([new Buffer("@import '" + global + "';"), file.contents]);
					})
					.pipe(_sass({
						includePaths    : [ global ],
						errLogToConsole : dev,
						outputStyle     : dev ? 'nested' : 'compressed',
						precision       : 3
					}))
					.pipe(_concat('bundle.css'));

				if (dev) stream = stream.pipe(_maps.write());

				stream
					.pipe(_gulp.dest(target))
					.on('finish', resolve)
					.on('error', reject);
			});
		});

		return _resolveTasks(tasks, done);
	});
};
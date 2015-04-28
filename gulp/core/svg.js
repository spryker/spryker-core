'use strict';

var _q    = require('q');
var _gulp = require('gulp');

var _getBundles   = require('./bundle');
var _getPaths     = require('./paths');
var _resolveTasks = require('./resolve');



/**
 * Asynchronously cleans the <em>bundle</em> svg spritemap destination folders
 * @param {String[]}  base The base paths
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after all directories are cleaned
 * @returns {_q}
 */
exports.cleanBundledSvg = function(base, dev, done) {
	var _del = require('del');

	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var target = _getPaths(bundle.path, 'src', 'svg', '');

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
 * Asynchronously creates svg spritemap file for all bundles
 * @param {String[]}  base The base paths
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after all files are created
 * @returns {_q}
 */
exports.createBundledSvg = function(base, dev, done) {
	var _glob = require('glob');
	var _svg  = require('gulp-svg-sprite');

	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var source = _getPaths(bundle.path, 'src', 'img', '**/*.svg');
			var target = _getPaths(bundle.path, 'src', 'svg', '');

			return _q.Promise(function(resolve, reject, notify) {
				_q
					.nfcall(_glob, source)
					.then(function(value) {
						if (value.length === 0) {
							resolve();

							return;
						}

						_gulp
							.src(source)
							.pipe(_svg({
								shape : {
									//dimension : {
									//	maxWidth  : 10,
									//	maxHeight : 10
									//},
									spacing : {
										padding : 1,
										box : 'content'
									}
								},
								mode : {
									css : {
										bust : false,
										dest : 'scss',
										sprite : '../images/sprite.svg',
										prefix : '%%svg-%s',
										dimensions : '-size',
										render : {
											scss : true
										}
									}
								}
							}))
							.pipe(_gulp.dest(target))
							.on('finish', resolve)
							.on('error', reject);
					});
			});
		});

		return _resolveTasks(tasks, done);
	});
};
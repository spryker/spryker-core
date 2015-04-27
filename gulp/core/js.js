'use strict';

var _q    = require('q');
var _gulp = require('gulp');

var _fs   = require('fs');
var _path = require('path');


var _getBundles   = require('./bundle');
var _getPaths     = require('./paths');
var _resolveTasks = require('./resolve');


/**
 * Asynchronously cleans the <em>bundle</em> js destination folders
 * @param {String[]}  base The base paths
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after all directories are cleaned
 * @returns {_q}
 */
exports.cleanBundledJs = function(base, dev, done) {
	var _del = require('del');

	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var target = _getPaths(bundle.path, 'dst', 'js', '');

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
 * Asynchronously creates a <code>bundle.js</code> file for each <em>bundle</em>
 * @param {String[]}  base The base paths
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after all files are created
 * @returns {_q}
 */
exports.createBundledJs = function(base, dev, done) {
	var _ugly = require('gulp-uglify');
	var _maps = require('gulp-sourcemaps');

	var _browserify  = require('browserify');
	var _vinylSource = require('vinyl-source-stream');
	var _vinylBuffer = require('vinyl-buffer');

	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function (bundle) {
			var source = _getPaths(bundle.path, 'src', 'js', 'index.js');
			var target = _getPaths(bundle.path, 'dst', 'js', 'bundle.js');

			return _q
				.nfcall(_fs.stat, source)
				.then(function (value) {
					return _q.Promise(function (resolve, reject, notify) {
						var stream = _browserify({
							entries : ['./' + source],
							debug : dev
						})
							.transform('brfs')
							.require('./' + source, {
								expose : bundle.name
							})
							.bundle()
							.pipe(_vinylSource(target))
							.pipe(_vinylBuffer());

						if (dev) stream = stream
							.pipe(_maps.init({
								loadMaps : true
							}))
							.pipe(_gulp.dest('./'))
							.pipe(_maps.write(_getPaths(bundle.path, 'src', 'js', '')));
						else stream = stream
							.pipe(_ugly())
							.pipe(_gulp.dest('./'));

						stream
							.on('finish', resolve)
							.on('error', reject);
					});
				}, function (reason) {
					return _q(true);
				});
		});

		return _resolveTasks(tasks, done);
	});
};


/**
 * Asynchronously lints the <em>js</em> files for all bundles
 * @param {String[]}  base The base paths
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after linting completes
 * @returns {_q}
 */
exports.lintSourceJs = function(base, dev, done) {
	var _jshint = require('gulp-jshint');

	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var source = _getPaths(bundle.path, 'src', 'js', '**/*.{js,json}');

			return _q.Promise(function(resolve, reject, notify) {
				_gulp
					.src(source)
					.pipe(_jshint('.jshintrc'))
					.pipe(_jshint.reporter('default'))
					.pipe(_jshint.reporter('fail'))
					.on('finish', resolve)
					.on('error', reject);
			});
		});

		return _resolveTasks(tasks, done);
	});
};

/**
 * Builds ngdoc documentation for suitable <em>js</em> files for all bundles
 * @param {String[]}  base The base paths
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after documentation is complete
 */
exports.docSourceJs = function(base, dev, done) {
	var _dgen  = require('dgeni');
	var _ngdoc = require('dgeni-packages/ngdoc');

	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {

			console.log(bundle);

			var config = new _dgen.Package('spy', [_ngdoc])
				.config(function(log, readFilesProcessor, templateFinder, writeFilesProcessor) {
					log.level = 'warn';

					readFilesProcessor.basePath = bundle.path;

					readFilesProcessor.sourceFiles = [{
						include  : _path.join(_getPaths.target.src, _getPaths.resource.js, '**/*.js'),
						exclude  : '**/{angular,angular-animate,angular-resource}.js',
						basePath : _path.join(_getPaths.target.src, _getPaths.resource.js)
					}];

					writeFilesProcessor.outputFolder = _path.join(_getPaths.target.dst, 'doc');
				});

			return _q(new _dgen([config]).generate());
		});

		console.log(tasks);

		return _resolveTasks(tasks, done);
	});
};
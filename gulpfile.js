'use strict';

var _gulp   = require('gulp');
var _svg    = require('gulp-svg-sprite');
var _sass   = require('gulp-sass');
var _jshint = require('gulp-jshint');
var _ugly   = require('gulp-uglify');
var _concat = require('gulp-concat');
var _maps   = require('gulp-sourcemaps');

var _browserify  = require('browserify');
var _vinylSource = require('vinyl-source-stream');
var _vinylBuffer = require('vinyl-buffer');

var _fs    = require('fs');
var _path  = require('path');
var _child = require('child_process');
var _del   = require('del');
var _glob  = require('glob');
var _q     = require('q');
var _chalk = require('chalk');


var _dirBase = [
	'src/SprykerFeature/Zed',
	'src/SprykerCore/Zed',
	'src/SprykerFeature/Zed'
];

var _dirCss = '/styles';
var _dirJs  = '/scripts';
var _dirSvg = '/sprite';
var _dirImg = '/images';
var _dirFnt = '/fonts';

var _directories = {
	'source' : 'Static/Assets',
	'target' : 'Static/Public'
};

var _bundlePaths = [];
var _bundles     = null;



/**
 * Returns an <code>Array</code> of globs built from the base directories and <code>path</code>
 * @param {String} path The path to be extended
 * @returns {Array}
 */
function _getSourceGlobs(path) {
	return _dirBase.map(function(item, index, source) {
		return _path.join(item, path);
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
						return _q
							.nfcall(_fs.stat, _path.join(dir, item))
							.then(function(stat) {
								if (stat.isDirectory()) bundles.push({
									name : item,
									path : _path.join(dir, item)
								});
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
function _getBundles(directories) {
	if (directories.length !== _bundlePaths.length) var build = true;
	else build = directories.some(function(item, index, source) {
		return item !== _bundlePaths[index];
	});

	if (build) {
		_bundles     = _buildBundles(_dirBase);
		_bundlePaths = directories;
	}

	var d = _q.defer();

	_bundles.then(d.resolve.bind(d));

	return d.promise;
}


/**
 * Returns a promise resolving <code>tasks</code>
 * @param {_q[]}      tasks  The Array of tasks
 * @param {Function} [done]  Called when promise resolves
 * @returns {_q}
 */
function _resolveTasks(tasks, done) {
	var q = _q.all(tasks);

	if (typeof done === 'function') q.then(done.bind(null, undefined));

	return q;
}


/**
 * Asynchronously creates a <code>bundle.css</code> file for each <em>bundle</em>
 * @param {String}    base  The bundle object
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after all files are created
 * @returns {_q}
 */
exports.createBundledCss = function(base, dev, done) {
	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var source = _path.join(bundle.path, _directories['source'], _dirCss, '/**/*.{scss,css}');
			var target = _path.join(bundle.path, _directories['target'], _dirCss);

			return _q.Promise(function(resolve, reject, notify) {
				var stream = _gulp.src(source);

				if (dev) stream = stream.pipe(_maps.init());

				stream = stream
					.pipe(_sass({
						'errLogToConsole' : dev,
						'outputStyle' : dev ? 'nested' : 'compressed',
						'precision' : 3
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

/**
 * Asynchronously creates a <code>bundle.js</code> file for each <em>bundle</em>
 * @param {String}    base  The bundle object
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after all files are created
 * @returns {_q}
 */
exports.createBundledJs = function(base , dev, done) {
	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function (bundle) {
			var source = _path.join(bundle.path, _directories['source'], _dirJs, '/index.js');
			var target = _path.join(bundle.path, _directories['target'], _dirJs, '/bundle.js');

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
							.pipe(_maps.write(_path.join(bundle.path, _directories['source'], _dirJs)));
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
 * @param {String}    base  The bundle object
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after linting completes
 * @returns {_q}
 */
exports.lintSourceJs = function(base, dev, done) {
	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var source = _path.join(bundle.path, _directories['source'], _dirJs, '/**/*.{js,json}');

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
 * @param {String}    base  The bundle object
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after documentation is complete
 */
exports.docSourceJs = function(base, dev, done) {
	var _dgen  = require('dgeni');
	var _ngdoc = require('dgeni-packages/ngdoc');

	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {

			var config = new _dgen.Package('spy', [_ngdoc])
				.config(function(log, readFilesProcessor, templateFinder, writeFilesProcessor) {
					log.level = 'warn';

					readFilesProcessor.basePath = _path.join(bundle.path);

					readFilesProcessor.sourceFiles = [{
						include  : _path.join(_directories['source'], _dirJs, '**/*.js'),
						exclude  : '**/{angular,angular-animate,angular-resource}.js',
						basePath : _path.join(_directories['source'], _dirJs)
					}];

					writeFilesProcessor.outputFolder = _path.join(_directories['target'], 'doc');
				});

			return _q(new _dgen([config]).generate());
		});

		return _resolveTasks(tasks, done);
	});
};


/**
 * Asynchronously creates svg spritemap file for all bundles
 * @param {String}    base  The bundle object
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after all files are created
 * @returns {_q}
 */
exports.createBundledSvg = function(base, dev, done) {
	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var source = _path.join(bundle.path, _directories['source'], _dirImg, '**/*.svg');
			var target = _path.join(bundle.path, _directories['source'], _dirSvg);

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

/**
 * Asynchronously copies the images of each <em>bundle</em>
 * @param {String}    base  The bundle object
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] Called after copying completes
 * @returns {_q}
 */
exports.copyBundledImages = function(base, dev, done) {
	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var source = _path.join(bundle.path, _directories['source'], _dirImg, '/**/*.{jpg,png,gif}');
			var sprite = _path.join(bundle.path, _directories['source'], _dirSvg, 'images/**/*.svg')
			var target = _path.join(bundle.path, _directories['target'], _dirImg);

			return _q.Promise(function(resolve, reject, notify) {
				_gulp
					.src([source, sprite])
					.pipe(_gulp.dest(target))
					.on('finish', resolve)
					.on('error', reject);
			});
		});

		return _resolveTasks(tasks, done);
	});
};

/**
 * Asynchronously copies the fonts of each <em>bundle</em>
 * @param {String}    base  The bundle object
 * @param {Boolean}   dev   <code>true</code> if development build, <code>false</code> otherwise
 * @param {Function} [done] called after copying completes
 * @returns {_q}
 */
exports.copyBundledFonts = function(base, dev, done) {
	return _getBundles(base).then(function(bundles) {
		var tasks = bundles.map(function(bundle) {
			var source = _path.join(bundle.path, _directories['source'], _dirFnt, '/**/*.{otf,ttf,woff,svg,eot}');
			var target = _path.join(bundle.path, _directories['target'], _dirFnt);

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


/**
 * Spawns a gulp process at <code>path</code>
 * @param {String}    path  The process path
 * @param {String}    task  The task name
 * @param {Function} [done] Called when <em>task</em> completes
 * @returns {undefined}
 */
exports.createCoreResources = function(path, task, done) {
	_child.spawn('gulp', [task], {
		cwd   : path,
		stdio : 'inherit'
	})
		.on('close', done.bind(null, undefined));
};

/**
 * Spawns a gulp <em>watcher</em> process at <code>path</code> and listens to completed tasks
 * @param {String}   path The process path
 * @param {Function} cb   The watcher callback
 * @returns {undefined}
 */
exports.watchCoreResources = function(path, cb) {
	var child = _child.spawn('gulp', ['watcher'], {
		cwd   : path,
		stdio : [
			process.stdin,
			'pipe',
			process.stderr
		]
	});

	child.stdout.on('data', function(chunk) {
		process.stdout.write(_chalk.grey(chunk));

		var str   = chunk.toString('utf8');
		var match = str.match(/^\[(?:\d{2}:){2}\d{2}\] Finished '([^']*)'/);

		if (match !== null) cb(match[1]);
	});
};



_gulp.task('clean-css', function(done) {
	_del([
		_dirBase + '/*' + _directories['target'] + _dirCss
	], done);
});

_gulp.task('clean-js', function(done) {
	_del([
		_dirBase + '/*' + _directories['target'] + _dirJs
	], done);
});

_gulp.task('clean-svg', function(done) {
	_del([
		_dirBase + '/*' + _directories['source'] + _dirSvg
	], done);
});

_gulp.task('clean-images', function(done) {
	_del([
		_dirBase + '/*' + _directories['target'] + _dirImg
	], done);
});

_gulp.task('clean-fonts', function(done) {
	_del([
		_dirBase + '/*' + _directories['target'] + _dirFnt
	], done);
});



_gulp.task('dev-css', ['clean-css'], function(done) {
	exports.createBundledCss(_dirBase, true, done);
});

_gulp.task('dist-css', ['dist-svg', 'clean-css'] , function(done) {
	exports.createBundledCss(_dirBase, false, done);
});



_gulp.task('test-js', function(done) {
	exports.lintSourceJs(_dirBase, false, done);
});

_gulp.task('doc-js', function(done) {
	exports.docSourceJs(_dirBase, false, done);
});

_gulp.task('dev-js', ['clean-js'] , function(done) {
	exports.createBundledJs(_dirBase, true, done);
});

_gulp.task('dist-js', ['clean-js'], function(done) {
	exports.createBundledJs(_dirBase, false, done);
});



_gulp.task('dev-svg', ['clean-svg'], function(done) {
	exports.createBundledSvg(_dirBase, true, done);
});

_gulp.task('dist-svg', ['clean-svg'], function(done) {
	exports.createBundledSvg(_dirBase, false, done);
});

_gulp.task('dev-images', ['clean-images'], function(done) {
	exports.copyBundledImages(_dirBase, true, done);
});

_gulp.task('dist-images', ['dist-svg', 'clean-images'], function(done) {
	exports.copyBundledImages(_dirBase, false, done);
});



_gulp.task('dev-fonts', ['clean-fonts'], function(done) {
	exports.copyBundledFonts(_dirBase, true, done);
});

_gulp.task('dist-fonts', ['clean-fonts'], function(done) {
	exports.copyBundledFonts(_dirBase, false, done);
});



_gulp.task('watcher', ['dev'], function() {
	_gulp.watch(_getSourceGlobs(_path.join('*', _directories['source'], _dirCss, '**/*.{scss,css}'))    , ['clean-css', 'dev-css']);
	_gulp.watch(_getSourceGlobs(_path.join('*', _directories['source'], _dirSvg, '**/*.{scss,css}'))    , ['clean-css', 'dev-css', 'clean-images', 'dev-images']);
	_gulp.watch(_getSourceGlobs(_path.join('*', _directories['source'], _dirJs , '**/*.{js,json,html}')), ['clean-js', 'dev-js']);
});


_gulp.task('test', [
	'test-js'
]);

_gulp.task('doc', [
	'doc-js'
]);




_gulp.task('dev', [
	'clean-css',
	'clean-js',
	'clean-images',
	'clean-fonts',
	'dev-css',
	'dev-js',
	'dev-images',
	'dev-fonts'
]);

_gulp.task('dist', [
	'clean-css',
	'clean-js',
	'clean-images',
	'clean-fonts',
	'dist-css',
	'dist-js',
	'dist-images',
	'dist-fonts'
]);


_gulp.task('default', [
	'dist'
]);
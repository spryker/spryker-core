'use strict';

var _gulp = require('gulp');

var _getBundles = require('../core/bundle');
var _getPaths   = require('../core/paths');


_gulp.task('watch', ['dev'], function() {
	_getBundles(require('../config').basePath).then(function(bundles) {
		var svg = bundles.map(function(item, index, source) {
			return _getPaths(item.path, 'src', 'svg', '**/*.{scss,css}');
		});

		var css = bundles.map(function(item, index, source) {
			return _getPaths(item.path, 'src', 'css', '**/*.{scss,css}');
		});

		var js  = bundles.map(function(item, index, source) {
			return _getPaths(item.path, 'src', 'js', '**/*.{js,json,html}');
		});

		_gulp.watch(svg, ['clean-css', 'dev-css', 'clean-images', 'dev-images']);
		_gulp.watch(css, ['clean-css', 'dev-css']);
		_gulp.watch(js , ['clean-js' , 'dev-js']);
	});
});
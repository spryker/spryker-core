'use strict';

var _gulp = require('gulp');

_gulp.task('install+dist', ['install'], function(done) {
	_gulp.start('dist');
});
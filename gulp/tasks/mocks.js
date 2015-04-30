var config = require('../config.js');
var gulp = require('gulp');
var del = require('del');

gulp.task('mocks', function(done) {
  return gulp
    .src(config.paths.source.mocks)
    .pipe(gulp.dest(config.paths.dest.mocks));
});

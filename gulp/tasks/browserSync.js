var browserSync = require('browser-sync');
var gulp        = require('gulp');
var config      = require('../config');

gulp.task('browserSync', function() {
  browserSync({
    server: {
      baseDir: config.paths.dest.root
    }
  });
});
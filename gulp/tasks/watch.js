var config = require('../config');
var gulp = require('gulp');

gulp.task('watch', ['browserSync'], function() {

  gulp.watch(config.paths.source.templates, ['templates']);
  gulp.watch(config.paths.source.styles,  ['styles']);
  gulp.watch(config.paths.source.scripts,   ['scripts']);
  gulp.watch(config.paths.source.images,  ['images']);
  gulp.watch(config.paths.source.fonts, ['fonts' ]);
});
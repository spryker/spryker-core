var config = require('../config');
var gulp = require('gulp');
var browserSync = require('browser-sync');

gulp.task('watch', function() {
  browserSync({
    server: {
      baseDir: config.paths.dest.root
    }
  });

  // gulp.watch(config.paths.source.templates, ['templates']);
  gulp.watch(config.paths.source.styles,  ['styles']);
  gulp.watch(config.paths.source.scripts,   ['scripts']);
  gulp.watch(config.paths.source.images,  ['images']);
  gulp.watch(config.paths.source.fonts, ['fonts' ]);
});
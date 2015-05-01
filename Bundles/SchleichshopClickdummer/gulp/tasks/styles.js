var config = require('../config.js');
var gulp = require('gulp');
var del = require('del');
var maps = require('gulp-sourcemaps');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var browserSync = require('browser-sync');
var reload = browserSync.reload;

gulp.task('styles', function(done) {
  // del([config.paths.dest.styles + config.globs.styles]);

  return gulp
    .src(config.paths.source.styles)
    .pipe(maps.init())
      .pipe(sass({
        errLogToConsole: true,
        style : 'expanded',
        sourceComments : 'normal'
      }))
      .pipe(autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
      }))
    .pipe(maps.write('./'))
    .pipe(gulp.dest(config.paths.dest.styles))
    .pipe(browserSync.reload({stream:true}));
});

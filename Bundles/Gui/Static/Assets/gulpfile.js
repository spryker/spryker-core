var gulp = require('gulp');
var uglify = require('gulp-uglify');
var less = require('gulp-less');
var concat = require('gulp-concat');
var path = require('path');

gulp.task('compile-css', function(){
    return gulp.src('LESS/style.less')
        .pipe(less({
            paths: [ path.join(__dirname, 'less', 'includes') ]
        }))
        //.pipe(uglify())
        .pipe(concat('style-gulp.css'))
        .pipe(gulp.dest('css/'))
});

gulp.task('default', [
    'compile-css'
]);

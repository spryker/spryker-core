'use strict';

require('gulp')
	.task('dist', [
		'clean-css',
		'clean-js',
		'clean-images',
		'clean-fonts',
		'dist-css',
		'dist-js',
		'dist-images',
		'dist-fonts'
	]);
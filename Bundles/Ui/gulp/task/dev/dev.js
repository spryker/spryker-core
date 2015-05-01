'use strict';

require('gulp')
	.task('dev', [
		'clean-css',
		'clean-js',
		'clean-images',
		'clean-fonts',
		'dev-css',
		'dev-js',
		'dev-images',
		'dev-fonts'
	]);
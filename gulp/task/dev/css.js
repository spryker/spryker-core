'use strict';

require('gulp')
	.task('dev-css', ['clean-css'], function(done) {
		require('../../core/css')
			.createBundledCss(require('../../config').basePath, true, done);
	});
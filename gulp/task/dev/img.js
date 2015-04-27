'use strict';

require('gulp')
	.task('dev-images', ['clean-images'], function(done) {
		require('../../core/img')
			.copyBundledImages(require('../../config').basePath, true, done);
	});
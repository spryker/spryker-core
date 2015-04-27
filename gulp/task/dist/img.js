'use strict';

require('gulp')
	.task('dist-images', ['clean-images'], function(done) {
		require('../../core/img')
			.copyBundledImages(require('../../config').basePath, false, done);
	});
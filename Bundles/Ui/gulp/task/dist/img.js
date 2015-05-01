'use strict';

require('gulp')
	.task('dist-images', ['clean-images', 'dist-svg'], function(done) {
		require('../../core/img')
			.copyBundledImages(require('../../config').basePath, false, done);
	});
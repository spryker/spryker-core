'use strict';

require('gulp')
	.task('clean-images', function(done) {
		require('../../core/img')
			.cleanBundledImages(require('../../config').basePath, false, done);
	});
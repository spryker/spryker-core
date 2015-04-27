'use strict';

require('gulp')
	.task('dist-js', ['clean-js'], function(done) {
		require('../../core/js')
			.createBundledJs(require('../../config').basePath, false, done);
	});
'use strict';

require('gulp')
	.task('dev-js', ['clean-js'] , function(done) {
		require('../../core/js')
			.createBundledJs(require('../../config').basePath, true, done);
	});
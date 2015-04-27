'use strict';

require('gulp')
	.task('clean-js', function(done) {
		require('../../core/js')
			.cleanBundledJs(require('../../config').basePath, false, done);
	});
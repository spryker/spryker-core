'use strict';

require('gulp')
	.task('clean-css', function(done) {
		require('../../core/css')
			.cleanBundledCss(require('../../config').basePath, false, done);
	});
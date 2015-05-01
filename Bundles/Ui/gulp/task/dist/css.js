'use strict';

require('gulp')
	.task('dist-css', ['dist-svg', 'clean-css'] , function(done) {
		require('../../core/css')
			.createBundledCss(require('../../config').basePath, false, done);
	});
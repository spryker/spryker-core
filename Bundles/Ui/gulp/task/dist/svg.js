'use strict';

require('gulp')
	.task('dist-svg', ['clean-svg'], function(done) {
		require('../../core/svg')
			.createBundledSvg(require('../../config').basePath, false, done);
	});
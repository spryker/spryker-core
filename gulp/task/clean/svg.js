'use strict';

require('gulp')
	.task('clean-svg', function(done) {
		require('../../core/svg')
			.cleanBundledSvg(require('../../config').basePath, false, done);
	});
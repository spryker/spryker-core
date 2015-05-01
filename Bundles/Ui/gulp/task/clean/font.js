'use strict';

require('gulp')
	.task('clean-fonts', function(done) {
		require('../../core/font')
			.cleanBundledFonts(require('../../config').basePath, false, done);
	});
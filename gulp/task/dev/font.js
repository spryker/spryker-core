'use strict';

require('gulp')
	.task('dev-fonts', ['clean-fonts'], function(done) {
		require('../../core/font')
			.copyBundledFonts(require('../../config').basePath, true, done);
	});
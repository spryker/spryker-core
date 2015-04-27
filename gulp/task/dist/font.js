'use strict';

require('gulp')
	.task('dist-fonts', ['clean-fonts'], function(done) {
		require('../../core/font')
			.copyBundledFonts(require('../../config').basePath, false, done);
	});
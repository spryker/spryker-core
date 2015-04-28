'use strict';

require('gulp')
	.task('test-js', function(done) {
		require('../../core/js')
			.lintSourceJs(require('../../config').basePath, false, done);
	});
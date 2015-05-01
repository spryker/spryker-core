'use strict';

require('gulp')
	.task('doc-js', function(done) {
		require('../../core/js')
			.docSourceJs(require('../../config').basePath, false, done);
	});
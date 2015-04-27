'use strict';

require('gulp')
	.task('install', function(done) {
		require('../core/install')
			.installModules(require('../config').basePath, false, done);
	});
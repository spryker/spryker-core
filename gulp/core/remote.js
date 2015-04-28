'use strict';

var _chalk = require('chalk');

var _child = require('child_process');



/**
 * Spawns a gulp process at <code>path</code>
 * @param {String}    path  The process path
 * @param {String}    task  The task name
 * @param {Function} [done] Called when <em>task</em> completes
 * @returns {undefined}
 */
exports.createCoreResources = function(path, task, done) {
	_child.spawn('gulp', [task], {
		cwd   : path,
		stdio : 'inherit'
	})
		.on('close', done.bind(null, undefined));
};


/**
 * Spawns a gulp <em>watcher</em> process at <code>path</code> and listens to completed tasks
 * @param {String}   path The process path
 * @param {Function} cb   The watcher callback
 * @returns {undefined}
 */
exports.watchCoreResources = function(path, cb) {
	var child = _child.spawn('gulp', ['watch'], {
		cwd   : path,
		stdio : [
			process.stdin,
			'pipe',
			process.stderr
		]
	});

	child.stdout.on('data', function(chunk) {
		process.stdout.write(_chalk.grey(chunk));

		var str   = chunk.toString('utf8');
		var match = str.match(/^\[(?:\d{2}:){2}\d{2}\] Finished '([^']*)'/);

		if (match !== null) cb(match[1]);
	});
};
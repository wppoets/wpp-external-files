'use strict';

module.exports = function (grunt) {

	// Load the project's grunt tasks from a directory
	require('grunt-config-dir')(grunt, {
		configDir: require('path').resolve('src/tasks')
	});

	grunt.registerTask('default', [
		'clean',
		'copy',
		'phplint',
	]);
	grunt.registerTask('dev', [
		'clean',
		'copy',
		'phplint',
		'watch',
	]);
};

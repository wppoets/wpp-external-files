'use strict';

var fs = require('fs');

module.exports = function watch(grunt) {
	// Load task
	grunt.loadNpmTasks('grunt-contrib-watch');

	// Return config
	return {
		root: {
			files: 'src/root/**/*',
			tasks: ['copy:root'],
		},
		php_classes: {
			files: 'src/php/classes/**/*.php',
			tasks: ['copy:php_classes','phplint:php_classes'],
		},
		php_functions: {
			files: 'src/php/functions/**/*.php',
			tasks: ['copy:php_functions','phplint:php_functions'],
		},
	};
};

'use strict';

var fs = require('fs');

module.exports = function copy(grunt) {
	// Load task
	grunt.loadNpmTasks('grunt-contrib-copy');

	var templateJson = {}
	if (fs.existsSync('src/config/template.json')) {
		templateJson = grunt.file.readJSON('src/config/template.json');
	} else {
		grunt.fail.warn('file src/template.json does not exists, something went wrong!');
	}

	// Return config
	return {
		root: {
			files: [{
				expand: true,
				cwd: 'src/root',
				src: ['**/*'],
				dest: ''
			}],
		},
		php_classes: {
			options: {
				process: function (content, srcpath) {
					return grunt.template.process(content, {data: templateJson});
				}
			},
			files: [{
				expand: true,
				cwd: 'src/php/classes',
				src: ['**/*.php'],
				dest: 'php/classes',
			}],
		},
		php_functions: {
			files: [{
				expand: true,
				cwd: 'src/php/functions',
				src: ['**/*.php'],
				dest: 'php/functions',
			}],
		},
	};
};

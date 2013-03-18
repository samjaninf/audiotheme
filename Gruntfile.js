'use strict';

var shell = require('shelljs');

module.exports = function(grunt) {

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// Check JavaScript for errors.
		jshint: {
			options: {
				bitwise: true,
				browser: true,
				curly: true,
				eqeqeq: true,
				eqnull: true,
				es5: true,
				esnext: true,
				immed: true,
				jquery: true,
				latedef: true,
				newcap: true,
				noarg: true,
				node: true,
				strict: false,
				trailing: true,
				undef: true,
				globals: {
					_: true,
					ajaxurl: true,
					audiotheme: true,
					audiothemePointers: true,
					AudiothemeMediaControl: true,
					jQuery: true,
					tb_remove: true,
					wp: true,
					wpPointerL10n: true,
				},
			},
			all: [
				'Gruntfile.js',
				'admin/js/*.js',
				'!admin/js/*.min.js',
			]
		},

		// Minify (could concatenate, too).
		uglify: {
			dist: {
				files: [
					{ src: 'admin/js/audiotheme-admin.js', dest: 'admin/js/audiotheme-admin.min.js' },
					{ src: 'admin/js/audiotheme-media.js', dest: 'admin/js/audiotheme-media.min.js' },
					{ src: 'admin/js/audiotheme-pointer.js', dest: 'admin/js/audiotheme-pointer.min.js' },
					{ src: 'admin/js/audiotheme-settings.js', dest: 'admin/js/audiotheme-settings.min.js' },
				]
			}
		},

		// Watch for changes.
		watch: {
			js: {
				files: ['<%= jshint.all %>'],
				tasks: ['jshint', 'uglify'],
			}
		},

		// Copy the plugin to a release/audiotheme-XXX directory.
		copy: {
			dist: {
				files: [
					{
						src: [
							'**',
							'!node_modules/**',
							'!release/**',
							'!Gruntfile.js',
							'!package.json',
							'!README.md',
							'!includes/lib/wp-less/**',
							'includes/lib/wp-less/lessc/lessc.inc.php',
							'includes/lib/wp-less/wp-less.php',
						],
						dest: 'release/audiotheme-<%= build %>/'
					}
				]
			}
		},

		/*shell: {
			gitTag: {
				command: 'git tag v<%= build %> -m "Version <%= build %>"'
			},
			makeDir: {
				command: 'mkdir test'
			},
		},*/

	});

	/**
	 * Load tasks.
	 */
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-shell');

	/**
	 * Register default task.
	 */
	grunt.registerTask('default', [
		'jshint',
		'uglify',
		'watch',
	]);

	/**
	 * Build a release.
	 *
	 * @todo generate pot files
	 *       git tag, commit, and push
	 *       copy to a release directory
	 *       clean source files
	 *       zip
	 *       push to remote server
	 */
	grunt.registerTask('release', function(arg1) {
		if (0 === arguments.length) {
			grunt.log.writeln('Pass in a version number.');
		} else {
			grunt.config.set('build', arg1);
			grunt.task.run('copy:dist');
		}
	});

};
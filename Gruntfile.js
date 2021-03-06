/* jshint node:true */
module.exports = function( grunt ) {
	var WP_DIR = '/srv/www/wordpress-trunk',
		THEME_NAME = 'umbra',
		SOURCE_DIR = 'src/',
		BUILD_DIR = 'build/';

	// Load tasks.
	require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );

	// Project configuration.
	grunt.initConfig( {
		clean: {
			all: [ BUILD_DIR ],
			dist: {
				dot: true,
				expand: true,
				cwd: BUILD_DIR,
				src: [
					'node_modules',
					'sass',
					'js/src',
					'layout',
					'.sass-cache'
				]
			}
		},

		copy: {
			all: {
				files: [{
					dot: true,
					expand: true,
					cwd: SOURCE_DIR,
					src: [
						'**',
						'!**/.{svn,git}/**', // Ignore version control directories.
						'!.DS_Store',
						'!package.json',
						'!Gruntfile.js',
						'!node_modules',
						'!.sass-cache',
						'!.gitignore'
					],
					dest: BUILD_DIR
				}]
			}
		},

		sass: {
			dev: {
				options: {
					noCache: false,
					sourcemap: true
				},
				expand: true,
				cwd: SOURCE_DIR + 'sass/',
				dest: SOURCE_DIR,
				ext: '.css',
				src: [ 'style.scss', 'editor-style.scss' ]
			},
			dist: {
				options: {
					noCache: true,
					sourcemap: false
				},
				expand: true,
				cwd: SOURCE_DIR + 'sass/',
				dest: BUILD_DIR,
				ext: '.css',
				src: [ 'style.scss', 'editor-style.scss' ]
			}
		},

		concat: {
			dev: {
				src: [ SOURCE_DIR + 'js/src/*.js' ],
				dest: SOURCE_DIR + 'js/' + THEME_NAME + '.js'
			},
			dist: {
				src: [ SOURCE_DIR + 'js/src/*.js' ],
				dest: BUILD_DIR + 'js/' + THEME_NAME + '.js'
			}
		},

		makepot: {
			dev: {
				options: {
					cwd: SOURCE_DIR,
					domainPath: '/languages',
					mainFile: 'style.css',
					potFilename: THEME_NAME + '.pot',
					potHeaders: {
						'poedit': true,
						'x-poedit-keywordslist': true
					},
					type: 'wp-theme',
					updateTimestamp: false
				}
			},
			dist: {
				options: {
					cwd: BUILD_DIR,
					domainPath: '/languages',
					mainFile: 'style.css',
					potFilename: THEME_NAME + '.pot',
					potHeaders: {
						'poedit': true,
						'x-poedit-keywordslist': true
					},
					type: 'wp-theme',
					updateTimestamp: false
				}
			}
		},

		wp_theme_check: {
			options: {
				path: WP_DIR
			},
			dist: {
				options: {
					theme: THEME_NAME + '/' + BUILD_DIR.replace( '/', '' )
				}
			}
		},

		pageres: {
			dist: {
				options: {
					url: 'vagrant.local',
					sizes: ['320x480', '320x568', '768x1024'],
					delay: '3',
					dest: 'screenshots'
				}
			}
		},

		compress: {
			main: {
				options: {
					archive: THEME_NAME + '.zip'
				},
				files: [
					{expand: true, cwd: BUILD_DIR, src: ['**'], dest: '/'}
				]
			}
		},

		watch: {
			css: {
				files: [SOURCE_DIR + 'sass/**'],
				tasks: ['sass:dev']
			},
			js: {
				files: [SOURCE_DIR + 'js/src/**'],
				tasks: ['concat:dev']
			}
		}
	} );

	// Register tasks.

	// Build task.
	grunt.registerTask( 'dev', ['sass:dev', 'concat:dev', 'makepot:dev'] );
	grunt.registerTask( 'build', ['clean:all', 'copy:all', 'sass:dist', 'concat:dist', 'clean:dist'] );
	grunt.registerTask( 'test', ['wp_theme_check:dist'] );
	grunt.registerTask( 'publish', ['build', 'makepot:dist', 'test', 'compress:main'] );

	grunt.registerTask( 'screenshot', ['pageres'] );

	// Default task.
	grunt.registerTask( 'default', ['dev'] );
};

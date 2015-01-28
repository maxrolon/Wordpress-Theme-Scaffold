var path = require('path');

module.exports = function(grunt) {

	// Load grunt tasks

	require('load-grunt-tasks')(grunt, {pattern: ['grunt-*', 'assemble']});
	
	// Configure Grunt tasks

	grunt.initConfig({
		
		browserify: {
			options: {
				browserifyOptions:{
			 		debug:true
				},
			 	preBundleCB: function(b) {
					b.plugin('minifyify', {
					 map: 'main.min.js.map',
					 output: 'js/dist/main.min.js.map',
				 });
				}
			},
			client: {
				src: ['js/src/modules/app.js'],
				dest: 'js/dist/main.min.js',
			}
		},
		
		sass: { 
			theme: {			 
				options: {											
					 style: 'compressed'
				},
				files: {													
					'css/styles.min.css':'sass/styles.scss'
				}
			},
			admin: {			 
				options: {											
					 style: 'compressed'
				},
				files: {													
					'css/admin.min.css':'sass/admin.scss'
				}
			}
		 },
		
		autoprefixer: {
			theme:{
				options: {
					map: true
				},
				dist: {
					src: 'css/styles.min.css',
					dest: 'css/styles.min.css'
				}
			},
			admin:{
				options: {
					map: true
				},
				dist: {
					src: 'css/admin.min.css',
					dest: 'css/admin.min.css'
				}
			}
		},
		
		image_resize: {
			 options: {
				width: '50%'
			},
			resized: {
				files: [
					{'img/sprite-sm.png': 'img/sprite.png'}
				]
			},
		},
		
		sprite:{
			ui: {
				engine: 'pngsmith',
				algorithm: 'binary-tree',
				src: 'img/ui/*.png',
				destImg: 'img/sprite.png',
				imgPath: '../../../img/sprite.png',
				cssTemplate: 'moustache/sprite.scss.mustache',
				destCSS: 'sass/partials/_sprite.scss',
				padding: 5
			}
		},

		watch: {
		 sass: {
			options:{
			 	livereload: false,
			},
			files: ['sass/**/*.scss','!sass/admin.scss'],
			tasks: ['sass:theme']
		 },
		 sass_theme: {
			options:{
			 	livereload: false,
			},
			files: ['sass/admin.scss'],
			tasks: ['sass:admin']
		 },
		 css_theme: {
				options: {
					livereload: true,
					spawn: false,
					reload: true
				},
				files: ['css/styles.min.css'],
				tasks: ['autoprefixer:theme']
			},
			css_admin: {
				options: {
					livereload: true,
					spawn: false,
					reload: true
				},
				files: ['css/admin.min.css'],
				tasks: ['autoprefixer:admin']
			},
			sprite: {
				options: {
					livereload: true
				},
				files: ['img/ui/*.png', 'moustache/sprite.less.mustache'],
				tasks: ['sprite']
			},
			image_resize: {
				options: {
					livereload: true
				},
				files: ['img/sprite.png'],
				tasks: ['image_resize']
			},
			browserify: {
				options: {
					livereload:true
				},
				files: ['js/src/modules/*.js'],
				tasks: ['browserify']
			}
		}
	});

	grunt.registerTask('build', [
		'sprite',
		'image_resize',
		'sass',
		'autoprefixer',
		'browserify'
		//'uglify'
	]);

	grunt.registerTask('dev', [
	 	'build',
		'watch',
	]);

	grunt.registerTask('default', ['dev']);
};
var path = require('path');

module.exports = function(grunt) {

  // Load grunt tasks

  require('load-grunt-tasks')(grunt, {pattern: ['grunt-*', 'assemble']});

 
 // Configure Grunt tasks

  grunt.initConfig({
    less: {
      options: {
        compress: true,
        yuicompress: true,
        optimization: 2,
        sourceMap: true,
        sourceMapFilename: 'css/styles.css.map',
        sourceMapURL: 'styles.css.map',
        sourceMapBasepath: 'css/',
        paths: ['less/']
      },
      dist: {
        files: { 
        	'css/styles.css': 'less/*.less', 'css/admin.css': 'less/partials/site-specific/admin.less'
        }
      }
    },

    autoprefixer: {
      options: {
        map: true
      },
      dist: {
        src: 'css/styles.css',
        dest: 'css/styles.css'
      }
    },
    
    sprite:{
      ui: {
        engine: 'pngsmith',
        algorithm: 'binary-tree',
        src: 'img/ui/*.png',
        destImg: 'img/sprite.png',
        imgPath: '../../../img/sprite.png',
        cssTemplate: 'moustache/sprite.less.mustache',
        destCSS: 'less/partials/site-specific/sprite.less',
        padding: 5
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

    uglify: {
       options: {
         sourceMap: true
       },
       dist: {
         files: [{
          expand: true,
          cwd: 'js/src',
          src: '**/*.js',
          dest: 'js/src/min'
				}]
       }
     },

    watch: {
      less: {
        options:{
	        livereload: true,
	        reload: true
        },
        files: ['less/**/*.less'],
        tasks: ['less']
      },
      css: {
        options: {
          livereload: true,
          spawn: false,
          reload: true
        },
        files: ['css/*.css'],
        tasks: ['autoprefixer']
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
       uglify: {
         options: {
           livereload: true
         },
         files: ['js/**/*.js']//,
         //tasks: ['uglify']
       }
      }
      
});

  grunt.registerTask('compile', [
    'sprite',
    'image_resize',
    'less',
    'autoprefixer'
  ]);

  grunt.registerTask('dev', [
    'compile',
    'watch',
  ]);
  
  grunt.registerTask('production', [
    'uglify',
    'compile',
  ]);

  grunt.registerTask('default', ['dev']);
};
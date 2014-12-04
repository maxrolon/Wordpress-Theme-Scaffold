var path = require('path');

module.exports = function(grunt) {

  // Load grunt tasks

  require('load-grunt-tasks')(grunt, {pattern: ['grunt-*', 'assemble']});

 
 // Configure Grunt tasks

  grunt.initConfig({
    
    browserify: {
    	options: {},
    	vendor: {
        src: [],
        dest: 'js/temp/vendor.js',
        options: {
          require: ['jquery', 'lodash'],
          alias: []
        }
      },
      client: {
        src: ['js/src/modules/app.js'],
        dest: 'js/temp/app.js',
        options: {
        	external: ['jquery','lodash']
	      },
      }
    },
    
		concat: {
      'js/temp/main.js': ['js/temp/vendor.js','js/temp/app.js']
    },
    
    uglify: {
       options: {
         sourceMap: true
       },
       js: {
         files: { 
        	'js/main.min.js': 'js/temp/main.js',
        }
       }
    },
    
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
        cssTemplate: 'moustache/sprite.less.mustache',
        destCSS: 'less/partials/site-specific/sprite.less',
        padding: 5
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
      browserify: {
        options: {
          livereload:false
        },
        files: ['js/src/modules/*.js', 'js/src/global/*.js'],
        tasks: ['browserify','concat']
      },
      uglify: {
        options: {
          livereload:true
        },
        files: ['js/temp/main.js'],
        tasks: ['uglify']
      }
    }
      
    

});

  grunt.registerTask('compile', [
    'sprite',
    'image_resize',
    'less',
    'autoprefixer',
    'browserify',
    'concat',
    'uglify'
  ]);

  grunt.registerTask('dev', [
    'compile',
    'watch',
  ]);

  grunt.registerTask('default', ['dev']);
};
var gulp = require('gulp');

var compass = require('gulp-compass');
var concat  = require('gulp-concat');
var minify  = require('gulp-minify-css');
var ug      = require('gulp-uglify');

gulp.task('application-js', function() {
	var files = [
		'js/main.js'
	];

	return gulp.src(files)
		.pipe(concat('hashtagsnow.min.js'))
		.pipe(ug())
		.pipe(gulp.dest('../public/js'));
});

gulp.task('sass', function() {
	return gulp.src('scss/**/*.scss')
		.pipe(compass({
			config_file: 'config.rb',
			css: '../public/css',
			sass: './scss'
		}));
});

gulp.task('watch', function() {
	gulp.watch(['scss/**/*.scss', 'js/**/*.js'], ['sass', 'application-js']);
});

gulp.task('default', ['sass', 'application-js', 'watch']);

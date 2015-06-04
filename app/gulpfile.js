var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    minifyCSS = require('gulp-minify-css'),
    concat = require('gulp-concat'),
    plumber    = require('gulp-plumber'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename');

var onError = function(err) {
    console.log(err);
}

gulp.task('styles', function() {

    var bootstrap = gulp.src('./app/Resources/public/css/bootstrap.scss')
        .pipe(plumber({
            errorHandler: onError
        }))
        .pipe(sass({ style: 'expanded' }))
        .pipe(minifyCSS({keepBreaks:true}))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./web/css/'));
});


gulp.task('scripts', function() {
    return gulp.src('./bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.js')
        .pipe(plumber({
            errorHandler: onError
        }))
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./web/js/'));
});

gulp.task('copy', function() {
    gulp.src('./bower_components/bootstrap-sass-official/assets/fonts/bootstrap/*.{ttf,woff,eof,svg,eot}')
    .pipe(gulp.dest('./web/fonts/bootstrap/'));
});

gulp.task('copy_jquery', function() {
    gulp.src('./bower_components/jquery/jquery.min.js')
    .pipe(gulp.dest('./web/js/'));
});

gulp.task('build', [
         'copy',
         'copy_jquery',
         'styles',
         'scripts'
]);

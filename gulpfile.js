'use strict';

var async = require('async');
var del = require('del');
var gulp = require('gulp');

gulp.task('clean', function(done) {
    return del('asset/vendor/pdfjs');
});

gulp.task('sync', function(done) {
    async.series([
        function (next) {
            gulp.src(['node_modules/pdf.js/build/generic/**'])
            .pipe(gulp.dest('asset/vendor/pdfjs/'))
            .on('end', next);
        }
    ], done);
});

gulp.task('default', gulp.series('clean', 'sync'));

gulp.task('install', gulp.task('default'));

gulp.task('update', gulp.task('default'));

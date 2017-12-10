'use strict';

const del = require('del');
const gulp = require('gulp');
const rename = require('gulp-rename');
const replace = require('gulp-string-replace');
const sourcemaps = require('gulp-sourcemaps');

gulp.task('clean', function(done) {
    return del('asset/vendor/pdfjs');
});

gulp.task('sync', gulp.series([
    function (next) {
        gulp.src(['node_modules/pdf.js/build/generic/**'])
        .pipe(gulp.dest('asset/vendor/pdfjs/'))
        .on('end', next);
    },
    function (next) {
        del('asset/vendor/pdfjs/web/compressed.tracemonkey-pldi-09.pdf');
        next();
    },
    function (next) {
        gulp.src([
            'node_modules/pdf.js/build/dist/build/pdf.min.js',
            'node_modules/pdf.js/build/dist/build/pdf.worker.min.js'
        ])
        .pipe(gulp.dest('asset/vendor/pdfjs/build/'))
        .on('end', next);
    }])
);

gulp.task('hack_viewer', function (done) {
    gulp.src(['node_modules/pdf.js/build/generic/web/viewer.css'])
    .pipe(rename('viewer-inline.css'))
    .pipe(sourcemaps.init())
    .pipe(replace('html \{', '.pdfjs-html {'))
    .pipe(replace('body \{', '.pdfjs {'))
    .pipe(replace(/^\* \{$/gm, '.pdfjs * {'))
    .pipe(replace(/body\,/gm, '.pdfjs,'))
    .pipe(replace(/^input,$/gm, '.pdfjs input,'))
    .pipe(replace(/^button,$/gm, '.pdfjs button,'))
    .pipe(replace(/^select \{$/gm, '.pdfjs select {'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('asset/vendor/pdfjs/web'));

    gulp.src(['asset/vendor/pdfjs/web/viewer.js'])
    .pipe(rename('viewer-inline.js'))
    .pipe(sourcemaps.init())
    .pipe(replace("var DEFAULT_URL = 'compressed.tracemonkey-pldi-09.pdf';", 'var DEFAULT_URL = documentUrl;'))
    .pipe(replace("PDFJS.workerSrc = '../build/pdf.worker.js';", "PDFJS.workerSrc = '';"))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('asset/vendor/pdfjs/web/'));

    done();
});

gulp.task('default', gulp.series('clean', 'sync', 'hack_viewer'));

gulp.task('install', gulp.task('default'));

gulp.task('update', gulp.task('default'));

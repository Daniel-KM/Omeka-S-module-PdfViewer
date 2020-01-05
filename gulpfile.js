'use strict';

const del = require('del');
const gulp = require('gulp');
const rename = require('gulp-rename');
const replace = require('gulp-string-replace');
const sourcemaps = require('gulp-sourcemaps');

gulp.task('clean', function(done) {
    del.sync('asset/vendor/pdf.js');
    done();
});

gulp.task('sync', gulp.series([
    function (next) {
        gulp.src(['node_modules/pdf.js/build/generic/**'])
        .pipe(gulp.dest('asset/vendor/pdf.js/'))
        .on('end', next);
    },
    function (next) {
        del.sync('asset/vendor/pdf.js/web/compressed.tracemonkey-pldi-09.pdf');
        next();
    },
    function (next) {
        gulp.src([
            'node_modules/pdf.js/build/dist/build/pdf.min.js',
            'node_modules/pdf.js/build/dist/build/pdf.worker.min.js'
        ])
        .pipe(gulp.dest('asset/vendor/pdf.js/build/'))
        .on('end', next);
    }])
);

const hack_pdfviewer_pdfjs = function (done) {
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
        .pipe(gulp.dest('asset/vendor/pdf.js/web'));

    gulp.src(['asset/vendor/pdf.js/web/viewer.js'])
        .pipe(rename('viewer-inline.js'))
        .pipe(sourcemaps.init())
        .pipe(replace("value: 'compressed.tracemonkey-pldi-09.pdf',", 'value: documentUrl,'))
        .pipe(replace('value: "compressed.tracemonkey-pldi-09.pdf",', 'value: documentUrl,'))
        .pipe(replace("value: '../build/pdf.worker.js',", "value: '',"))
        .pipe(replace('value: "../build/pdf.worker.js",', "value: '',"))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('asset/vendor/pdf.js/web/'))
        .on('end', done);
};

gulp.task('default', gulp.series('clean', 'sync', hack_pdfviewer_pdfjs));

gulp.task('install', gulp.task('default'));

gulp.task('update', gulp.task('default'));

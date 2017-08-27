var async = require('async');
var del = require('del');
var fs = require('fs');
var glob = require("glob");
var gulp = require('gulp');
var rename = require("gulp-rename");
var runSequence = require('run-sequence');

gulp.task('sync', function(cb) {

    async.series([
        function (next) {
            gulp.src(['node_modules/pdfjs/dist/generic/pdfjs-*/**'])
            .pipe(gulp.dest('asset/vendor/'))
            .on('end', next);
        }
    ], cb);
});

gulp.task('clean', function(cb) {
    return del('asset/vendor/pdfjs');
});

gulp.task('rename', function(cb) {
    var file = glob.sync('asset/vendor/pdfjs-*/');
    fs.renameSync(file[0], 'asset/vendor/pdfjs/');
    cb();
});

gulp.task('default', function(cb) {
    runSequence('clean', 'sync', 'rename', cb);
});

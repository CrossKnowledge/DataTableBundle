var gulp = require('gulp');
var gutil = require('gulp-util');
var debug = require('gulp-debug');

var plumber = require('gulp-plumber');
var minifyCss = require('gulp-minify-css');
var rename = require('gulp-rename');
var sass = require('gulp-sass');

var browserify = require('browserify');
var babelify = require('babelify');
var babel = require('gulp-babel');

var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var uglify = require('gulp-uglify');
var merge = require('merge-stream');

var runSequence = require('run-sequence');
var watch = require('gulp-watch');
var del = require('del');

var moduleconfig = {
    moduleName: 'ckdatable',
    srcRoot: '.',
    compiledRoot: '../public/assets',
    vendorRoot: './bower_components',
    publicVendorRoot: '../public/vendor',
    scripts: ['scripts/**/*.js'],
    styles: ['sass/**/*.scss'],
    tmp: 'scripts/tmp'
};

gulp.task('scripts-ie8', function () {
    inject = require('gulp-inject-string');
    var sourceFile = moduleconfig.srcRoot + '/scripts/main.js';

    //Copy file
    gulp.src(moduleconfig.srcRoot + '/scripts/_ckdatatable.js').pipe(gulp.dest(moduleconfig.tmp));

    //Inject the require
    gulp.src(sourceFile)
        .pipe(inject.after("var datatables = require('./_ckdatatable');", '\nrequire("babel-polyfill");\n'))
        .pipe(gulp.dest(moduleconfig.tmp));

    var tmpFile = moduleconfig.tmp + '/main.js';

    return browserify(tmpFile, {debug: true}).transform("babelify", {presets: ["es2015"]})
        .bundle().on('error', function (err) {
            gutil.log('Error in ' + tmpFile + ':');
            gutil.log(gutil.colors.red(err));
            this.emit('end');
        })
        .pipe(source(moduleconfig.moduleName  + '_ie8.js'))
        .pipe(buffer())
        .pipe(uglify())
        .pipe(gulp.dest(moduleconfig.compiledRoot + '/js'));

});

gulp.task('scripts-other', function () {
    var sourceFile = moduleconfig.srcRoot + '/scripts/main.js';

    return browserify(sourceFile, {debug: true}).transform("babelify", {presets: ["es2015"]})
        .bundle().on('error', function (err) {
            gutil.log('Error in ' + sourceFile + ':');
            gutil.log(gutil.colors.red(err));
            this.emit('end');
        })
        .pipe(source(moduleconfig.moduleName + '.js'))
        .pipe(buffer())
        .pipe(uglify())
        .pipe(gulp.dest(moduleconfig.compiledRoot + '/js'));

});


gulp.task('scripts', ['scripts-ie8', 'scripts-other']);


gulp.task('standalone-libs', function() {
    var standaloneVendors = ['datatables'];
    var moveFolders = merge();

    standaloneVendors.map(function (lvendor) {
        moveFolders.add(
            gulp.src([
                    moduleconfig.vendorRoot + '/' + lvendor + '/**/*.ttf',
                    moduleconfig.vendorRoot + '/' + lvendor + '/**/*.woff',
                    moduleconfig.vendorRoot + '/' + lvendor + '/**/*.js',
                    moduleconfig.vendorRoot + '/' + lvendor + '/**/*.png',
                    moduleconfig.vendorRoot + '/' + lvendor + '/**/*.svg',
                    moduleconfig.vendorRoot + '/' + lvendor + '/**/*.eot',
                    moduleconfig.vendorRoot + '/' + lvendor + '/**/*.css',
                    moduleconfig.vendorRoot + '/' + lvendor + '/**/*.swf'
                ], {
                    base: moduleconfig.vendorRoot + '/' + lvendor
                })
                .pipe(gulp.dest(moduleconfig.publicVendorRoot + '/' + lvendor)));
    });

    return moveFolders;
});

gulp.task('sass', function () {
    return gulp.src(moduleconfig.srcRoot + "/sass/main.scss")
        .pipe(rename(moduleconfig.moduleName + '.css'))
        .pipe(sass().on('error', sass.logError))
        .pipe(plumber())
        .pipe(minifyCss())
        .pipe(gulp.dest(moduleconfig.compiledRoot + '/css'))
});

gulp.task('build', function (callback) {
        runSequence(
            'clean',
            'clean-tmp',
            ['sass', 'scripts', 'standalone-libs'],
            'clean-tmp',
            callback
        );
    }
);

gulp.task('watch', function () {
    gulp.watch(moduleconfig.styles, ['sass']);
    gulp.watch(moduleconfig.scripts, ['scripts']);
});

gulp.task('clean', function () {
    return del([moduleconfig.compiledRoot + '/*'], {
        force: true
    });
});

gulp.task('clean-tmp', function () {
    return del([moduleconfig.tmp], {
        force: true
    });
});

gulp.task('default', ['build', 'watch']);
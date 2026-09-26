/* eslint-disable no-console */
/**
 * fSD Theme
 * Copyright 2021 fSD. All rights reserved.
 */

 "use strict";

 import del from "del";
 import browserSync from "browser-sync";
 import gulp from "gulp";
 import gulpLoadPlugins from "gulp-load-plugins";
 import pkg from "./package.json";
//  import gulpSass from 'gulp-sass';
//  import nodeSass from 'node-sass';
 import gulpSass from 'gulp-sass'; import sassCompiler from 'sass';
 //import imagemin from 'imagemin';

//  let sass = gulpSass(nodeSass);
const sass = gulpSass(sassCompiler);

 
 const themeRoot = "wp-content/themes/vladastheme",
   srcRoot = themeRoot + "/src",
   distRoot = themeRoot + "/bundles",
   $ = gulpLoadPlugins(),
   reload = browserSync.reload,
   banner = [
     "/**",
     " * <%= pkg.name %> - <%= pkg.description %>",
     " * @version v<%= pkg.version %>",
     " * @license <%= pkg.license %>",
     " * @copyright 2021 fSD",
     " * @link https://fsd.rs",
     " */",
     "",
   ].join("\n"),
   AUTOPREFIXER_BROWSERS = ["last 2 versions"],
   SOURCES = [
     srcRoot + "/scripts/src/script.js",
   ];
 
 gulp.task("mocha", () => {
   return gulp
     .src(srcRoot + "/scripts/test/**/*.js", { read: false })
     .pipe($.mocha({ reporter: "spec" }));
 });
 
 // Lint javascript
 gulp.task("lint", () => {
   return gulp
     .src([srcRoot + "/scripts/src/script.js", "gulpfile.babel.js"])
     .pipe(reload({ stream: true, once: true }))
     .pipe($.eslint())
     .pipe($.eslint.format());
 });
 
 // Clean images
 gulp.task("clean:images", () => del(distRoot + "/images/*/*"));
 
 // Minify images
 gulp.task(
   "images:min",
   gulp.series("clean:images", () => {
     return gulp
       .src(srcRoot + "/images/**/*")
       .pipe(imagemin())
       .pipe(gulp.dest(distRoot + "/images"));
   })
 );
 
 // Sass lint
 gulp.task("sass-lint", () => {
   return gulp
     .src(srcRoot + "/styles/scss/**/*.scss")
     .pipe($.sassLint())
     .pipe($.sassLint.format())
     .pipe($.sassLint.failOnError());
 });
 
 //Compile and Automatically Prefix Stylesheets (dev)
 gulp.task(
   "styles:dev",
   gulp.series("sass-lint", () => {
     return gulp
       .src(srcRoot + "/styles/scss/style.scss")
       .pipe($.sourcemaps.init())
       .pipe($.plumber())
       .pipe(
         sass({
           precision: 10,
         })
       )
       .on("error", function (err) {
         browserSync.notify(err.message, 4000);
         console.log(err.message); //eslint-disable-line no-console
       })
       .pipe($.autoprefixer(AUTOPREFIXER_BROWSERS))
       .pipe($.sourcemaps.write("."))
       .pipe(gulp.dest(themeRoot))
       .pipe(reload({ stream: true }))
       .pipe($.size({ title: "styles" }));
   })
 );
 
 gulp.task("clean:map", () => del(themeRoot + "/style.css.map"));
 
 // Compile and Automatically Prefix Stylesheets (production)
 gulp.task(
   "styles:build",
   gulp.series("sass-lint", "clean:map", () => {
     return gulp
       .src(srcRoot + "/styles/scss/style.scss")
       .pipe($.plumber())
       .pipe(
         sass({
           precision: 10,
         })
       )
       .on("error", function (err) {
         browserSync.notify(err.message, 4000);
         console.log(err.message); //eslint-disable-line no-console
       })
       .pipe($.autoprefixer(AUTOPREFIXER_BROWSERS))
       .pipe(gulp.dest(themeRoot))
       .pipe($.csso())
       .pipe($.header(banner, { pkg }))
       .pipe(gulp.dest(themeRoot))
       .pipe(reload({ stream: true, once: true }))
       .pipe($.size({ title: "styles" }));
   })
 );
 
 // Concatenate And Minify JavaScript
 gulp.task("scripts:build", () => {
   return gulp
     .src(SOURCES)
     .pipe($.sourcemaps.init())
     .pipe($.babel())
     .pipe($.uglify())
     .pipe($.concat("scripts.min.js"))
     .pipe($.header(banner, { pkg }))
     .pipe(gulp.dest(distRoot + "/scripts"))
     .pipe($.size({ title: "scripts" }));
 });
 
 let watch = () => {
   gulp.watch(
     [srcRoot + "/scripts/src/script.js", "gulpfile.babel.js"],
     gulp.series("lint", "scripts:build")
   );
   gulp.watch(srcRoot + "/styles/scss/**/*.scss", gulp.series("styles:dev"));
   gulp.watch(srcRoot + "/images/**/*", gulp.series("images:min"));
 };
 
 gulp.task("serve", () => {
   browserSync.init({
     open: "false",
     proxy: pkg.config.domain,
   });
 
   watch();
 });
 
 gulp.task(
   "build",
   gulp.series("styles:build", "scripts:build", function (done) {
     done();
   })
 );
 
const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/*mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');*/

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', '../resources/sass')
   .styles([
   		'resources/sass/app.css',
   		'resources/js/datetimepicker/jquery.datetimepicker.css',
   		'resources/js/select2/css/select2.css',
   		'resources/js/select2/css/select2-bootstrap4.css',
   		'resources/js/charjs/Chart.css'
   	], 'public/css/app.css');

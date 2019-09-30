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

mix
    .styles([
        'resources/CoolAdmin-master/css/font-face.css',
        'resources/CoolAdmin-master/vendor/font-awesome-4.7/css/font-awesome.min.css',
        'resources/CoolAdmin-master/vendor/font-awesome-5/css/fontawesome-all.min.css',
        'resources/CoolAdmin-master/vendor/mdi-font/css/material-design-iconic-font.min.css',
    ], 'public/css/fonts.css')

    .styles([
        'resources/CoolAdmin-master/vendor/bootstrap-4.1/bootstrap.min.css',
        'resources/CoolAdmin-master/vendor/animsition/animsition.min.css',
        'resources/CoolAdmin-master/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css',
        'resources/CoolAdmin-master/vendor/wow/animate.css',
        'resources/CoolAdmin-master/vendor/css-hamburgers/hamburgers.min.css',
        'resources/CoolAdmin-master/vendor/slick/slick.css',
        'resources/CoolAdmin-master/vendor/select2/select2.min.css',
        'resources/CoolAdmin-master/vendor/perfect-scrollbar/perfect-scrollbar.css',
        'node_modules/datatables.net-dt/css/jquery.dataTables.min.css',
        'node_modules/multi.js/dist/multi.min.css',
        'node_modules/select2/dist/css/select2.css',
        'node_modules/jquery-confirm/dist/jquery-confirm.min.css',
        'node_modules/flatpickr/dist/flatpickr.min.css',
        'resources/CoolAdmin-master/vendor/Checkbox2Button/css/checkbox2button.css',
        'node_modules/ckeditor/contents.css',
    ], 'public/css/vendor.css')

    .styles([
        'resources/CoolAdmin-master/css/theme.css'
    ], 'public/css/theme.css')

    .styles(['resources/CoolAdmin-master/css/login.css'], 'public/css/login.css')

    .scripts([
        'resources/CoolAdmin-master/vendor/jquery-3.2.1.min.js',
        'resources/CoolAdmin-master/vendor/bootstrap-4.1/popper.min.js',
        'resources/CoolAdmin-master/vendor/bootstrap-4.1/bootstrap.min.js',
        'resources/CoolAdmin-master/vendor/slick/slick.min.js',
        'resources/CoolAdmin-master/vendor/wow/wow.min.js',
        'resources/CoolAdmin-master/vendor/animsition/animsition.min.js',
        'resources/CoolAdmin-master/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js',
        'resources/CoolAdmin-master/vendor/counter-up/jquery.waypoints.min.js',
        'resources/CoolAdmin-master/vendor/counter-up/jquery.counterup.min.js',
        'resources/CoolAdmin-master/vendor/circle-progress/circle-progress.min.js',
        'resources/CoolAdmin-master/vendor/perfect-scrollbar/perfect-scrollbar.js',
        'resources/CoolAdmin-master/vendor/chartjs/Chart.bundle.min.js',
        'resources/CoolAdmin-master/vendor/select2/select2.min.js',
        'resources/CoolAdmin-master/vendor/perfect-scrollbar/perfect-scrollbar.js',
        'node_modules/jquery-mask-plugin/dist/jquery.mask.min.js',
        'node_modules/multi.js/dist/multi.min.js',
        'node_modules/select2/dist/js/select2.js',
        'node_modules/jquery-confirm/dist/jquery-confirm.min.js',
        'node_modules/flatpickr/dist/flatpickr.min.js',
        'resources/CoolAdmin-master/vendor/Checkbox2Button/js/checkbox2button.min.js',
    ], 'public/js/vendors.js')
    .scripts([
        'node_modules/datatables.net/js/jquery.dataTables.min.js',
        'node_modules/datatables.net-dt/js/dataTables.dataTables.min.js'
    ], 'public/js/datatables.js')

    .scripts(['resources/CoolAdmin-master/js/main.js'], 'public/js/main.js')

    .copyDirectory('resources/CoolAdmin-master/vendor/font-awesome-4.7/fonts', 'public/fonts')
    .copyDirectory('resources/CoolAdmin-master/vendor/font-awesome-5/webfonts', 'public/webfonts')
    .copyDirectory('resources/CoolAdmin-master/vendor/mdi-font/fonts', 'public/fonts')
    .copyDirectory('resources/CoolAdmin-master/fonts/poppins', 'public/fonts/poppins')
    .copyDirectory('node_modules/select2/dist/js/i18n', 'public/i18n')
    .copyDirectory('node_modules/flatpickr/dist/l10n/pt.js', 'public/l10n/pt.js')
    .options({
        processCssUrls: false
    })
    .version()

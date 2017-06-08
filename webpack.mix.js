const { mix } = require('laravel-mix');

mix.js('resources/assets/js/app.js', 'public/js')
    .autoload({
        jquery: ['$', 'window.jQuery', 'jQuery']
    })
    .extract(['axios', 'jquery', 'lodash', 'bootstrap-sass', 'vue'])
    .sass('resources/assets/sass/app.scss', 'public/css');

const mix = require('laravel-mix');

mix.js('resources/js/back/back.js','public/js')
    .js('resources/js/front/front.js','public/js')
    .js('resources/js/login/login.js','public/js')
    .vue()
    .version();
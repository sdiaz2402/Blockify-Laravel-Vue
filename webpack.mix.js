const mix = require('laravel-mix');
const path = require('path')
const tailwindcss = require('tailwindcss');
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

mix.js('resources/js/main.js', 'public/js')
    .sass('resources/js/styles/app.scss', 'public/css')
    // .postCss("resources/js/styles/app.css", "public/css", [
    //     require("tailwindcss"),
    //    ])
    .options({
        postCss: [ tailwindcss('./tailwind.config.js') ],
    })
    .webpackConfig({
        resolve: {
        alias: {
            '@': path.resolve(
                __dirname, 'resources/js')
            },
          modules: [
            path.resolve(__dirname),
            path.resolve('./node_modules/'),
            path.resolve('./resources/')
          ]
        }
      });


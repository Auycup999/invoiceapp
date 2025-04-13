const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .vue() // ✅ ต้องมีเพื่อรองรับไฟล์ .vue
   .sass('resources/sass/app.scss', 'public/css');
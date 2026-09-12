import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import {readdirSync} from 'node:fs';

// Every file in resources/css/themes/ becomes its own entry point, so a theme
// can be restyled by editing only its own file. Adding a theme is just a matter
// of dropping a new .css file into that directory.
const themeEntries = readdirSync('resources/css/themes')
    .filter((file) => file.endsWith('.css'))
    .map((file) => `resources/css/themes/${file}`);

export default defineConfig({
    plugins: [
        laravel({
            input: [
                ...themeEntries,
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});

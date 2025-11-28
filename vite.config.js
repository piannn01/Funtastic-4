import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    // PENTING: bikin semua asset ditulis dengan path root domain
    // jadi URL-nya: /build/assets/xxx.css bukan /Funtastic-4/public/...
    base: '/',

    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,

            // biar outputnya konsisten ke public/build
            buildDirectory: 'build',
            publicDirectory: 'public',
        }),
        tailwindcss(),
    ],

    build: {
        // pastiin manifest selalu dibuat
        manifest: true,

        // pastiin outputnya masuk public/build
        outDir: 'public/build',

        // jangan hapus folder build lain duluan kalau ada proses copy/symlink
        emptyOutDir: true,
    },
});

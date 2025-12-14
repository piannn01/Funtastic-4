import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  /**
   * Pakai base relatif supaya asset tetap ketemu
   * baik di root domain maupun kalau public_html pakai symlink/copy.
   * Hasil URL jadi: build/assets/xxx.js
   */
  base: './',

  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js'
      ],
      refresh: true,

      // default laravel outputnya ke public/build
      buildDirectory: 'build',
    }),
    tailwindcss(),
  ],

  build: {
    manifest: true,
    outDir: 'public/build',
    emptyOutDir: true,
  },
});

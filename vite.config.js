import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// Catatan: Project ini menggunakan Tailwind CSS via CDN (lihat resources/views/layouts/app.blade.php)
// supaya mahasiswa tidak perlu menjalankan "npm run build" untuk melihat tampilan.
// File vite.config.js ini disiapkan sebagai opsi jika ingin migrasi ke Tailwind build process nanti.
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});

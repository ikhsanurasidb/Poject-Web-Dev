import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    server: {
        port: 3000,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        proxy: {
          '/api' : {
            target: "http://127.0.0.1:8000",
            changeOrigin: true,
            headers: {
              Accept: "application/json",
              "Content-Type": "application/json",
            }
          }
        }
      }
});

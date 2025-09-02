import { defineConfig } from 'vite';
import { resolve } from 'path';
import fs from 'fs';
import { copyFileSync, mkdirSync, existsSync, readdirSync } from 'fs';

// Функция для копирования иконок
function copyIcons() {
    const srcDir = resolve(__dirname, 'src/icons');
    const destDir = resolve(__dirname, 'assets/icons');

    // Создаем директорию назначения, если она не существует
    if (!existsSync(destDir)) {
        mkdirSync(destDir, { recursive: true });
    }

    // Копируем все файлы из src/icons в assets/icons
    const iconFiles = readdirSync(srcDir);
    iconFiles.forEach(file => {
        copyFileSync(resolve(srcDir, file), resolve(destDir, file));
        console.log(`Copied icon: ${file}`);
    });

    console.log('All icons copied successfully!');
}

// Копируем иконки при запуске
copyIcons();

export default defineConfig({
    // Плагины для Vite
    plugins: [
        {
            name: 'watch-icons',
            // Копируем иконки при старте сервера
            buildStart() {
                copyIcons();
            },
            // Наблюдаем за изменениями в директории иконок
            handleHotUpdate({ file, server }) {
                if (file.includes('src/icons/')) {
                    copyIcons();
                    // Обновляем страницу для отображения изменений
                    server.ws.send({ type: 'full-reload' });
                    return [];
                }
            }
        }
    ],
    build: {
        sourcemap: true,
        outDir: './assets',
        emptyOutDir: false,
        rollupOptions: {
            input: {
                'scripts/admin': resolve(__dirname, 'src/scripts/admin.ts'),
                'scripts/frontend': resolve(__dirname, 'src/scripts/frontend.ts'),
            },
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: 'js/[name].js',
                assetFileNames: ({ names }) => {
                    if (/\.(gif|jpeg|jpg|png|webp)$/.test(names[0] ?? "")) {
                        return "images/[name][extname]";
                    }
                    if (/\.svg$/.test(names[0] ?? "")) {
                        return "svgs/[name][extname]";
                    }
                    if (/\.css$/.test(names[0] ?? "")) {
                        return "styles/[name][extname]";
                    }
                    return "[name][extname]";
                },
            },
            // Подавляем предупреждения, если нужно
            onwarn(warning, warn) {
                if (warning.code === 'CIRCULAR_DEPENDENCY') return;
                warn(warning);
            },
            // Хук после сборки для копирования иконок
            plugins: [
                {
                    name: 'copy-icons-plugin',
                    closeBundle() {
                        copyIcons();
                    }
                }
            ],
        },
        write: true,
    },
    server: {
        port: 3000,
        strictPort: true,
        watch: {
            // Наблюдаем за изменениями в директории иконок
            ignored: ['!**/src/icons/**'],
        },
        https: {
            key: fs.readFileSync('/home/yarops/srvdirs/certs/certs/localhost.key'),
            cert: fs.readFileSync('/home/yarops/srvdirs/certs/certs/localhost.crt'),
        },
        hmr: {
            port: 3000,
            protocol: 'wss',
        },
        host: 'localhost',
        cors: {
            origin: '*', // Разрешаем запросы с любых доменов
            methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
            credentials: true,
        },
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers': 'Origin, X-Requested-With, Content-Type, Accept',
        },
    },
    css: {
        devSourcemap: true,
    },
});
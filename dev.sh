#!/bin/bash

# Проверяем, установлен ли pnpm
if ! command -v pnpm &> /dev/null; then
    echo "pnpm не установлен. Используем npm."
    npm run dev
else
    echo "Запускаем сервер разработки с помощью pnpm..."
    pnpm run dev
fi

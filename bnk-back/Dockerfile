# Используем официальный легковесный образ PHP 8.3 FPM на Alpine Linux
FROM php:8.3-fpm-alpine

# Устанавливаем рабочую директорию
WORKDIR /var/www

# 1. Установка системных зависимостей, необходимых для работы расширений PHP
# linux-headers нужен для некоторых расширений, libpng/oniguruma/libxml для gd/mbstring/xml
RUN apk add --no-cache \
    curl \
    git \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# 2. Установка расширений PHP
# pdo_mysql - для базы данных
# mbstring, exif, pcntl, bcmath, gd - стандартный набор для Laravel
# opcache - критически важен для производительности в проде
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd opcache zip

# 3. Установка Redis (опционально, но очень рекомендуется для кэша и очередей)
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del pcre-dev $PHPIZE_DEPS

# 4. Установка Composer
# Копируем бинарник прямо из официального образа Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Копируем только файлы зависимостей (для кэширования слоев Docker)
COPY composer.json composer.lock ./

# 6. Устанавливаем зависимости Composer
# --no-dev: не ставим phpunit и прочее для тестов
# --no-scripts: не запускаем скрипты ларавел, так как код еще не скопирован полностью
# --no-autoloader: ускоряет этот шаг
RUN composer install --prefer-dist --no-dev --no-scripts --no-autoloader --no-interaction

# 7. Копируем весь остальной код приложения
COPY . .

# 8. Финализируем Composer (генерация автозагрузчика)
RUN composer dump-autoload --optimize

# 9. Настройка прав доступа
# Пользователь www-data (стандартный для php-fpm) должен иметь права на запись в storage и cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 10. Настройка PHP для продакшена (можно вынести в отдельные файлы ini)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Переключаемся на пользователя www-data для безопасности
USER www-data

# Открываем порт 9000 (стандартный для PHP-FPM)
EXPOSE 9000

# Команда запуска
CMD ["php-fpm"]

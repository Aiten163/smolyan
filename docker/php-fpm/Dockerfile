FROM php:8.3-fpm

# Установка всех необходимых зависимостей
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libgd-dev \
    mariadb-client \
    wget \
    unzip \
    curl \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    libavif-bin \
    git \
    && docker-php-ext-configure exif \
    && docker-php-ext-install -j$(nproc) exif pdo_mysql pcntl \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-webp=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Копирование конфигурации PHP
COPY ./conf.d /usr/local/etc/php/conf.d/

# Установка рабочего каталога
WORKDIR /var/www

# Копирование исходного кода приложения
COPY . .

# Изменение прав доступа к папкам storage и bootstrap/cache
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Настройка параметров PHP через Dockerfile
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_input_vars = 5000" >> /usr/local/etc/php/conf.d/uploads.ini
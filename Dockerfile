FROM php:8.1-fpm

# 1. ติดตั้ง system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim unzip git curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    npm \
    nodejs

# 2. ติดตั้ง PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# 3. ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. ตั้ง working directory
WORKDIR /var/www

# 5. คัดลอก source code
COPY . .

# 6. ติดตั้ง package
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# 7. Set Permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# 8. Expose port
EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000

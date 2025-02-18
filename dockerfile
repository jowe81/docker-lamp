FROM php:8.2-apache

# Install required packages
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libxml2-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        gd \
        intl \
        mbstring \
        zip \
        bcmath \
        exif \
        opcache \
    && docker-php-ext-enable opcache

# Enable Apache mod_rewrite (useful for Laravel, WordPress, etc.)
#RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80


FROM php:8.4-apache

# Install PostgreSQL extensions, zip tools, and git
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Change document root to public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations and start Apache
CMD php artisan migrate --force && apache2-foreground
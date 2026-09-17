# Menggunakan image PHP resmi dengan Web Server Apache
FROM php:8.2-apache

# Instal ekstensi yang dibutuhkan Laravel & PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql

# Aktifkan mod_rewrite Apache untuk routing Laravel
RUN a2enmod rewrite

# Set folder kerja ke dalam direktori Apache
WORKDIR /var/www/html

# Salin seluruh kode proyek ke dalam container
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Atur izin folder storage dan cache agar bisa ditulis
RUN chown -R www-data:www-data storage bootstrap/cache

# Arahkan Apache DocumentRoot ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
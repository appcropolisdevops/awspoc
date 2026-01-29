FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Create database directory with proper permissions
RUN mkdir -p /var/www/data && chown -R www-data:www-data /var/www/data

EXPOSE 9000
CMD ["php-fpm"]

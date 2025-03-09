FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Apache configuration
COPY docker/backend/apache.conf /etc/apache2/sites-available/000-default.conf

# Copy initialization script
COPY docker/backend/init.sh /usr/local/bin/init.sh
RUN chmod +x /usr/local/bin/init.sh

# Enable Apache modules
RUN a2enmod rewrite headers

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html

# Expose port
EXPOSE 80

# Start Apache and run initialization script
CMD ["/bin/bash", "-c", "/usr/local/bin/init.sh && apache2-foreground"]
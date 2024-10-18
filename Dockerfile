# Use the official PHP image as a base image
FROM php:7.4-apache

# Install necessary PHP extensions and nano
RUN apt-get update && apt-get install -y nano \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite (necessary for CodeIgniter)
RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the contents of your application to the working directory
COPY . .

# Install dependencies with Composer (if applicable)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Debug: List files in the application directory
RUN ls -la /var/www/html

# Set proper permissions
RUN find /var/www/html -type f -exec chmod 644 {} \; && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    chown -R www-data:www-data /var/www/html

# Expose port 80 to the outside world
EXPOSE 80

RUN echo "ServerName app.gooproper.com" >> /etc/apache2/apache2.conf

CMD ["apache2-foreground"]

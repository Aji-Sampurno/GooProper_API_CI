# Use the official PHP image as a base image
FROM php:7.4-apache

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache mod_rewrite (necessary for CodeIgniter)
RUN a2enmod rewrite

# Install Composer (if using Composer for dependencies)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the contents of your application to the working directory
COPY . /var/www/html

# Install dependencies with Composer (if applicable)
RUN composer install --no-dev --optimize-autoloader

# Debug: List files in the application directory
RUN ls -la /var/www/html

# Debug: List files in vendor directory
RUN find /var/www/html/vendor -type f

# Apply a fix for vfsStream.php if it exists
RUN if [ -f /var/www/html/vendor/mikey179/vfsstream/src/main/php/org/bovigo/vfs/vfsStream.php ]; then \
    sed -i s/name{0}/name[0]/ /var/www/html/vendor/mikey179/vfsstream/src/main/php/org/bovigo/vfs/vfsStream.php; \
fi

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 to the outside world
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]

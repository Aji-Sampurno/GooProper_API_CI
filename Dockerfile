# Use the official PHP image as a base image
FROM php:7.4-apache

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the contents of your application to the working directory
COPY . /var/www/html

# Set proper permissions (optional)
RUN chown -R www-data:www-data /var/www/html

# Enable Apache mod_rewrite (if needed by your application)
RUN a2enmod rewrite

# Expose port 80 to the outside world
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]

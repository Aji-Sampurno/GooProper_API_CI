# Use official Debian 12 (Bookworm) as the base image
FROM debian:bookworm-slim AS build

# Prevent interactive prompts during package installation
ENV DEBIAN_FRONTEND=noninteractive

# Install necessary packages and PHP extensions
RUN apt-get update && apt-get install -y \
    curl \
    php \
    php-cli \
    php-json \
    php-common \
    php-mysqli \
    php-zip \
    php-gd \
    php-mbstring \
    php-curl \
    php-xml \
    php-bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory for the build
WORKDIR /var/www/html

# Copy application files from the current directory
COPY . .

# Install application dependencies
RUN composer install --no-dev

# Stage 2: Runtime Stage
FROM debian:bookworm-slim

# Prevent interactive prompts during package installation
ENV DEBIAN_FRONTEND=noninteractive

# Install Apache and PHP with necessary extensions
RUN apt-get update && apt-get install -y \
    apache2 \
    libapache2-mod-php \
    php \
    php-fpm \
    php-json \
    php-common \
    php-mysqli \
    php-zip \
    php-gd \
    php-mbstring \
    php-curl \
    php-xml \
    php-bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy application files from the build stage
COPY --from=build /var/www/html /var/www/html

# Enable Apache modules
RUN a2enmod rewrite

# Set permissions for the web directory
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Set the ServerName to suppress the warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2ctl", "-D", "FOREGROUND"]

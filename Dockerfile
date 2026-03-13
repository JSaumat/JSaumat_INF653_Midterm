# Use the official PHP 8.2 image with Apache pre-installed
FROM php:8.2-apache

# Enable the Apache rewrite module
# This allows the server to use rewrite rules defined in .htaccess
RUN a2enmod rewrite

# Update package lists and install PostgreSQL development libraries
# These libraries are required so PHP can compile PostgreSQL extensions
RUN apt-get update && apt-get install -y libpq-dev \
  && docker-php-ext-install pdo pdo_pgsql pgsql \
  && rm -rf /var/lib/apt/lists/*

# Modify Apache configuration to allow .htaccess files to override settings
# This enables rewrite rules and environment variables defined in .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copy all project files into the Apache web root inside the container
# /var/www/html is the default directory served by Apache
COPY . /var/www/html/

# Expose port 80 so the container can receive HTTP requests
# This is the standard port used by Apache web servers
EXPOSE 80
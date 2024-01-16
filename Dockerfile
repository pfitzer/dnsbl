# Uses official PHP Image with CLI
FROM php:8.2-cli

# Update packages and install git and unzip, they are needed by composer
RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ git zip unzip

# Install needed php extensions
RUN docker-php-ext-install intl opcache

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Switch to the /app directory so that commands run inside this directory
WORKDIR /app

# Copy the application files to the Docker image
COPY . .

# Install PHP dependencies
RUN composer install

# Command to execute PHPUnit tests
CMD ["./vendor/bin/phpunit", "-c", "phpunit.xml"]
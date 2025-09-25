FROM php:8.2.12-apache

# Declare composer version
ENV COMPOSER_VERSION=2.7.7

# Cài đặt các dependencies và extensions trước
RUN apt-get update \
    && apt-get install -y \
        curl \
        zip \
        unzip \
        git \
        supervisor \
        libpng-dev \
        libpq-dev \
        libzip-dev \
        libexif-dev \
        gnupg \
        tzdata \
    && docker-php-ext-install \
        bcmath \
        gd \
        opcache \
        sockets \
        zip \
        exif \
        pdo \
        pdo_mysql \
        mysqli \
    && docker-php-ext-enable pdo_mysql mysqli

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Bật mod_rewrite và headers
RUN a2enmod rewrite headers

# Cấu hình Apache để cho phép .htaccess
RUN echo "<Directory /var/www/html>" > /etc/apache2/conf-available/allow-htaccess.conf
RUN echo "    Options Indexes FollowSymLinks" >> /etc/apache2/conf-available/allow-htaccess.conf
RUN echo "    AllowOverride All" >> /etc/apache2/conf-available/allow-htaccess.conf
RUN echo "    Require all granted" >> /etc/apache2/conf-available/allow-htaccess.conf
RUN echo "</Directory>" >> /etc/apache2/conf-available/allow-htaccess.conf
RUN a2enconf allow-htaccess

# Cấu hình document root
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=$COMPOSER_VERSION

# Tạo user
RUN useradd -ms /bin/bash -u 1000 app

# Thiết lập working directory
WORKDIR /var/www/html

# Thay đổi quyền sở hữu (sẽ được mount từ host)
RUN chown -R www-data:www-data /var/www/html

# Copy source code - NHƯNG sẽ bị ghi đè bởi volume mount
# COPY src/ ./
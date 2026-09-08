FROM php:8.3-apache

# 1. Installation des paquets système requis et extensions PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Configuration PHP pour Apache et CLI (variables d'environnement)
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/' "$PHP_INI_DIR/php.ini"

# 3. Activation du module Apache rewrite
RUN a2enmod rewrite

# 4. Configuration du DocumentRoot vers public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Autoriser .htaccess (AllowOverride All)
RUN echo "<Directory /var/www/html/public/>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# 6. Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 7. Copie des descripteurs de dépendances et installation (mise en cache Docker)
COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --prefer-dist || true

# 8. Copie du code source complet
COPY . /var/www/html

# 9. Autoload final et permissions www-data
RUN composer dump-autoload --optimize && \
    chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]

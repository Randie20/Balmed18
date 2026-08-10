FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . /var/www/html/

RUN mkdir -p /var/www/html/uploads/products \
    && chown -R www-data:www-data /var/www/html/uploads

RUN sed -ri \
    -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/html

EXPOSE 80

CMD ["apache2-foreground"]

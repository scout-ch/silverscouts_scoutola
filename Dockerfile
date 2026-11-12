FROM php:8.5-apache AS base

RUN apt-get update && \
    apt-get install -y --no-install-recommends unzip wget curl ca-certificates \
        libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev libxpm-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm && \
    docker-php-ext-install -j$(nproc) mysqli pdo pdo_mysql gd && \
    docker-php-ext-enable mysqli pdo_mysql gd && \
    a2enmod rewrite

RUN bash -c "wget -O- https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer"

FROM base AS development

RUN apt-get install -y --no-install-recommends bash gpg git
WORKDIR /workspace

FROM base AS production
ARG OSCLASS_VERSION=latest

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/apt/*

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
COPY .docker/security.ini /usr/local/etc/php/conf.d/security.ini

WORKDIR /var/www/html
COPY --chown=www-data:www-data .docker/setup_osclass.sh .docker/setup_osclass.sh
RUN .docker/setup_osclass.sh ${OSCLASS_VERSION} --rm
COPY --chown=www-data:www-data .htaccess .htaccess
COPY --chown=www-data:www-data config.php config.php
COPY --chown=www-data:www-data plugins oc-content/plugins
COPY --chown=www-data:www-data theme/patch oc-content/themes/sigma
RUN cd /var/www/html/oc-content/plugins/oidc && composer install --no-dev --prefer-dist --no-interaction --no-progress \
    && cd /var/www/html/oc-content/themes/sigma && patch --merge --verbose -p1 < ./theme.patch && rm ./theme.patch

# Health check for Kubernetes
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/index.php || exit 1

EXPOSE 80

CMD ["apache2-foreground"]

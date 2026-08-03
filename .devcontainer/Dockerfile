FROM php:8.5-cli
RUN apt-get update && \
    apt-get install -y unzip gpg git wget \
        libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev libxpm-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm 
RUN docker-php-ext-install mysqli pdo pdo_mysql gd && \
    docker-php-ext-enable mysqli pdo_mysql gd
RUN bash -c "wget -O- https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer"

FROM php:7.4-rc-alpine
LABEL maintainer="Kuroki Almajiro <kuroki@almajiro.net"

RUN apk add libssh2-dev libzip-dev autoconf build-base unzip git

RUN cd /tmp && \
    git clone https://git.php.net/repository/pecl/networking/ssh2.git && \
    cd /tmp/ssh2 && \
    phpize && \
    ./configure && \
    make && make install && \
    echo "extension=ssh2.so" > /usr/local/etc/php/conf.d/ext-ssh2.ini && \
    rm -rf /tmp/ssh2

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

USER ${APP_UID}
WORKDIR /var/www

ENTRYPOINT ["/bin/sh", "-c", "while :; do sleep 1; done"]

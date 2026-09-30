FROM php:8.5.11-apache

ARG UID=1000
ARG GID=1000

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
    && docker-php-ext-install \
        zip \
        pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.10.3 /usr/bin/composer /usr/bin/composer

# タイムゾーンを日本時間に（OS と PHP の両方）
ENV TZ=Asia/Tokyo
RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && echo ${TZ} > /etc/timezone
COPY conf/php.ini /usr/local/etc/php/conf.d/zz-custom.ini

# phper ユーザーを作成（UID/GID はホストに合わせられるよう引数化）
RUN groupadd -g ${GID} phper \
    && useradd -m -u ${UID} -g phper -s /bin/bash phper

# Apache のワーカーを phper で動かす
ENV APACHE_RUN_USER=phper \
    APACHE_RUN_GROUP=phper

# Slim はフロントコントローラ方式なので DocumentRoot を public/ に、rewrite を有効化
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
COPY --chown=phper:phper conf/apache2.conf /etc/apache2/apache2.conf
COPY --chown=phper:phper conf/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY --chown=phper:phper conf/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
RUN a2enmod rewrite actions

WORKDIR /var/www/html
RUN chown phper:phper /var/www/html

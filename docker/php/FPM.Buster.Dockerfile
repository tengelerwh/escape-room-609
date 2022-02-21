# Use this image as base
FROM php:8.1-fpm-buster

# Require arguments about the user/group this container uses
ARG CTN_UID
ARG CTN_GID
ARG CTN_USR
ARG CTN_GRP
ARG CTN_HOME
ARG XDG_REMOTE_HOST

ENV nodejsVersion=latest
# Grab Composer from its image and set it up
ENV COMPOSER_HOME=${CTN_HOME}/.composer \
    COMPOSER_CACHE_DIR=${CTN_HOME}/.composer/cache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN mkdir -p ${COMPOSER_HOME} ${COMPOSER_CACHE_DIR} && \
    chown -R ${CTN_USR}:${CTN_GRP} ${COMPOSER_HOME}

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Custom PHP configuration
COPY php/override-php.ini ${PHP_INI_DIR}/conf.d/zz-override-php.ini
RUN mkdir -p /var/cache/php/app /var/cache/php/opcache_lock /var/cache/php/session /var/cache/php/soap_wsdl_cache /var/cache/php/sys /var/cache/php/upload /var/cache/php/xdebug_gc_stats /var/cache/php/xdebug_profiler /var/cache/php/xdebug_trace /var/log/php/scripts && \
    chown -R ${CTN_USR}:${CTN_GRP} /var/cache/php /var/log/php && \
    cp "${PHP_INI_DIR}/php.ini-development" "${PHP_INI_DIR}/php.ini"

RUN apt-get update \
    && apt-get install git curl gnupg -y

# install php extensions
RUN apt-get install -y zlib1g-dev libpq-dev git libicu-dev libxml2-dev libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure pdo_mysql \
    && docker-php-ext-configure zip  \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install zip xml \
    && docker-php-ext-enable zip

# Map the user/group this container uses to the uid/gid of YOUR user
# This ensures that:
# - the same user runs CLI and FPM
# - it has write-access to mounted files
# - files created by it are owned by YOUR user on YOUR machine
# Thus, it eliminates ANY need for special file-modes, 0644 and 0755 will do just fine!
RUN if [ ${CTN_UID:-0} -ne 0 ] && [ ${CTN_GID:-0} -ne 0 ]; then \
        OLD_UID="$(id -u ${CTN_USR})"  \
        && OLD_GID="$(id -g ${CTN_GRP})" \
        && deluser ${CTN_USR} \
        && if getent group ${CTN_GRP}; then delgroup ${CTN_GRP}; fi \
        && addgroup --gid ${CTN_GID} ${CTN_GRP} \
        && adduser --disabled-password --uid ${CTN_UID} --ingroup ${CTN_GRP} --home ${CTN_HOME} --gecos "" ${CTN_USR} \
        && echo "Added group ${CTN_GRP} with user ${CTN_USR}" \
        && install -d -m 0755 -o ${CTN_USR} -g ${CTN_GRP} ${CTN_HOME} \
        && chown --changes --silent --no-dereference --recursive --from=${OLD_UID}:${OLD_GID} ${CTN_UID}:${CTN_GID} \
            ${CTN_HOME} \
            /var/cache/php \
            /var/log/php \
#            /var/www/escape-room \
    ;fi

# make sure no xdebug is on when running composer
RUN alias composer='XDEBUG_MODE=off \composer'

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime

RUN curl -fsSL https://deb.nodesource.com/setup_17.x | bash -
RUN apt-get install -y nodejs

RUN npm install npm@${nodejsVersion} -g \
    && npm install n --global \
    && n latest \
    && npm install --global yarn \
    && npm install --global --save-dev webpack webpack-cli

RUN echo 'PHP:' && php -v \
    && echo 'Node:' && node -v \
    && echo 'Npm:' && npm -v \
    && echo 'Yarn:' && yarn -v \
    && echo 'Webpack:' && webpack -v \
    && "date"

# fix for webpack Error code: 'ERR_OSSL_EVP_UNSUPPORTED'
ENV NODE_OPTIONS=--openssl-legacy-provider

USER ${CTN_USR}:${CTN_GRP}

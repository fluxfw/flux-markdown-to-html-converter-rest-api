FROM php:8.2-cli-alpine AS build

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN (mkdir -p /build/flux-markdown-to-html-converter-rest-api/libs/commonmark && cd /build/flux-markdown-to-html-converter-rest-api/libs/commonmark && composer require league/commonmark:2.3.8 --ignore-platform-reqs)

RUN (mkdir -p /build/flux-markdown-to-html-converter-rest-api/libs/flux-markdown-to-html-converter-api && cd /build/flux-markdown-to-html-converter-rest-api/libs/flux-markdown-to-html-converter-api && wget -O - https://github.com/fluxfw/flux-markdown-to-html-converter-api/archive/refs/tags/v2023-01-30-1.tar.gz | tar -xz --strip-components=1)

RUN (mkdir -p /build/flux-markdown-to-html-converter-rest-api/libs/flux-rest-api && cd /build/flux-markdown-to-html-converter-rest-api/libs/flux-rest-api && wget -O - https://github.com/fluxfw/flux-rest-api/archive/refs/tags/v2023-01-30-1.tar.gz | tar -xz --strip-components=1)

COPY . /build/flux-markdown-to-html-converter-rest-api

FROM php:8.2-cli-alpine

RUN apk add --no-cache libstdc++ && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev openssl-dev && \
    (mkdir -p /usr/src/php/ext/swoole && cd /usr/src/php/ext/swoole && wget -O - https://pecl.php.net/get/swoole | tar -xz --strip-components=1) && \
    docker-php-ext-configure swoole --enable-openssl --enable-swoole-curl && \
    docker-php-ext-install -j$(nproc) swoole && \
    docker-php-source delete && \
    apk del .build-deps

USER www-data:www-data

EXPOSE 9501

ENTRYPOINT ["/flux-markdown-to-html-converter-rest-api/bin/server.php"]

COPY --from=build /build /

ARG COMMIT_SHA
LABEL org.opencontainers.image.revision="$COMMIT_SHA"

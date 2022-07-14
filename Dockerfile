FROM php:cli-alpine AS build

RUN (mkdir -p /flux-namespace-changer && cd /flux-namespace-changer && wget -O - https://github.com/fluxfw/flux-namespace-changer/releases/download/v2022-07-12-1/flux-namespace-changer-v2022-07-12-1-build.tar.gz | tar -xz --strip-components=1)

RUN (mkdir -p /build/flux-markdown-to-html-converter-rest-api/libs/flux-autoload-api && cd /build/flux-markdown-to-html-converter-rest-api/libs/flux-autoload-api && wget -O - https://github.com/fluxfw/flux-autoload-api/releases/download/v2022-07-12-1/flux-autoload-api-v2022-07-12-1-build.tar.gz | tar -xz --strip-components=1 && /flux-namespace-changer/bin/change-namespace.php . FluxAutoloadApi FluxMarkdownToHtmlConverterRestApi\\Libs\\FluxAutoloadApi)

RUN (mkdir -p /build/flux-markdown-to-html-converter-rest-api/libs/flux-markdown-to-html-converter-api && cd /build/flux-markdown-to-html-converter-rest-api/libs/flux-markdown-to-html-converter-api && wget -O - https://github.com/fluxfw/flux-markdown-to-html-converter-api/releases/download/v2022-07-12-1/flux-markdown-to-html-converter-api-v2022-07-12-1-build.tar.gz | tar -xz --strip-components=1 && /flux-namespace-changer/bin/change-namespace.php . FluxMarkdownToHtmlConverterApi FluxMarkdownToHtmlConverterRestApi\\Libs\\FluxMarkdownToHtmlConverterApi)

RUN (mkdir -p /build/flux-markdown-to-html-converter-rest-api/libs/flux-rest-api && cd /build/flux-markdown-to-html-converter-rest-api/libs/flux-rest-api && wget -O - https://github.com/fluxfw/flux-rest-api/releases/download/v2022-07-12-1/flux-rest-api-v2022-07-12-1-build.tar.gz | tar -xz --strip-components=1 && /flux-namespace-changer/bin/change-namespace.php . FluxRestApi FluxMarkdownToHtmlConverterRestApi\\Libs\\FluxRestApi)

COPY . /build/flux-markdown-to-html-converter-rest-api

FROM php:cli-alpine

LABEL org.opencontainers.image.source="https://github.com/fluxfw/flux-markdown-to-html-converter-rest-api"

RUN apk add --no-cache libstdc++ && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev openssl-dev && \
    (mkdir -p /usr/src/php/ext/swoole && cd /usr/src/php/ext/swoole && wget -O - https://pecl.php.net/get/swoole | tar -xz --strip-components=1) && \
    docker-php-ext-configure swoole --enable-openssl --enable-swoole-curl --enable-swoole-json && \
    docker-php-ext-install -j$(nproc) swoole && \
    docker-php-source delete && \
    apk del .build-deps

USER www-data:www-data

EXPOSE 9501

ENTRYPOINT ["/flux-markdown-to-html-converter-rest-api/bin/server.php"]

COPY --from=build /build /

ARG COMMIT_SHA
LABEL org.opencontainers.image.revision="$COMMIT_SHA"

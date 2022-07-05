ARG FLUX_AUTOLOAD_API_IMAGE=docker-registry.fluxpublisher.ch/flux-autoload-api
ARG FLUX_MARKDOWN_TO_HTML_CONVERTER_API_IMAGE=docker-registry.fluxpublisher.ch/flux-markdown-to-html-converter-api
ARG FLUX_NAMESPACE_CHANGER_IMAGE=docker-registry.fluxpublisher.ch/flux-namespace-changer
ARG FLUX_REST_API_IMAGE=docker-registry.fluxpublisher.ch/flux-rest-api

FROM $FLUX_AUTOLOAD_API_IMAGE:v2022-06-22-1 AS flux_autoload_api
FROM $FLUX_MARKDOWN_TO_HTML_CONVERTER_API_IMAGE:v2022-07-05-1 AS flux_markdown_to_html_converter_api
FROM $FLUX_REST_API_IMAGE:v2022-06-29-2 AS flux_rest_api

FROM $FLUX_NAMESPACE_CHANGER_IMAGE:v2022-06-23-1 AS build_namespaces

COPY --from=flux_autoload_api /flux-autoload-api /code/flux-autoload-api
RUN change-namespace /code/flux-autoload-api FluxAutoloadApi FluxMarkdownToHtmlConverterRestApi\\Libs\\FluxAutoloadApi

COPY --from=flux_markdown_to_html_converter_api /flux-markdown-to-html-converter-api /code/flux-markdown-to-html-converter-api
RUN change-namespace /code/flux-markdown-to-html-converter-api FluxMarkdownToHtmlConverterApi FluxMarkdownToHtmlConverterRestApi\\Libs\\FluxMarkdownToHtmlConverterApi

COPY --from=flux_rest_api /flux-rest-api /code/flux-rest-api
RUN change-namespace /code/flux-rest-api FluxRestApi FluxMarkdownToHtmlConverterRestApi\\Libs\\FluxRestApi

FROM alpine:latest AS build

COPY --from=build_namespaces /code/flux-autoload-api /build/flux-markdown-to-html-converter-rest-api/libs/flux-autoload-api
COPY --from=build_namespaces /code/flux-markdown-to-html-converter-api /build/flux-markdown-to-html-converter-rest-api/libs/flux-markdown-to-html-converter-api
COPY --from=build_namespaces /code/flux-rest-api /build/flux-markdown-to-html-converter-rest-api/libs/flux-rest-api
COPY . /build/flux-markdown-to-html-converter-rest-api

RUN (cd /build && tar -czf flux-markdown-to-html-converter-rest-api.tar.gz flux-markdown-to-html-converter-rest-api)

FROM php:8.1-cli-alpine

LABEL org.opencontainers.image.source="https://github.com/flux-caps/flux-markdown-to-html-converter-rest-api"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"
LABEL flux-docker-registry-rest-api-build-path="/flux-markdown-to-html-converter-rest-api.tar.gz"

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

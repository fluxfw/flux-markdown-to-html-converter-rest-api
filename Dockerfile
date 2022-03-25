ARG ALPINE_IMAGE=alpine:latest
ARG FLUX_AUTOLOAD_API_IMAGE=docker-registry.fluxpublisher.ch/flux-autoload/api:latest
ARG FLUX_MARKDOWN_TO_HTML_CONVERTER_API=docker-registry.fluxpublisher.ch/flux-markdown-to-html-converter/api:latest
ARG FLUX_NAMESPACE_CHANGER_IMAGE=docker-registry.fluxpublisher.ch/flux-namespace-changer:latest
ARG FLUX_REST_API_IMAGE=docker-registry.fluxpublisher.ch/flux-rest/api:latest
ARG PHP_CLI_IMAGE=php:cli-alpine
ARG SWOOLE_SOURCE_URL=https://pecl.php.net/get/swoole

FROM $FLUX_AUTOLOAD_API_IMAGE AS flux_autoload_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE AS flux_autoload_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxAutoloadApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxMarkdownToHtmlConverterRestApi\\Libs\\FluxAutoloadApi
COPY --from=flux_autoload_api /flux-autoload-api /code
RUN $FLUX_NAMESPACE_CHANGER_BIN

FROM $FLUX_MARKDOWN_TO_HTML_CONVERTER_API AS flux_markdown_to_html_converter_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE AS flux_markdown_to_html_converter_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxMarkdownToHtmlConverterApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxMarkdownToHtmlConverterRestApi\\Libs\\FluxMarkdownToHtmlConverterApi
COPY --from=flux_markdown_to_html_converter_api /flux-markdown-to-html-converter-api /code
RUN $FLUX_NAMESPACE_CHANGER_BIN

FROM $FLUX_REST_API_IMAGE AS flux_rest_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE AS flux_rest_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxRestApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxMarkdownToHtmlConverterRestApi\\Libs\\FluxRestApi
COPY --from=flux_rest_api /flux-rest-api /code
RUN $FLUX_NAMESPACE_CHANGER_BIN

FROM $ALPINE_IMAGE AS build

COPY --from=flux_autoload_api_build /code /flux-markdown-to-html-converter-rest-api/libs/flux-autoload-api
COPY --from=flux_markdown_to_html_converter_api_build /code /flux-markdown-to-html-converter-rest-api/libs/flux-markdown-to-html-converter-api
COPY --from=flux_rest_api_build /code /flux-markdown-to-html-converter-rest-api/libs/flux-rest-api
COPY . /flux-markdown-to-html-converter-rest-api

FROM $PHP_CLI_IMAGE
ARG SWOOLE_SOURCE_URL

LABEL org.opencontainers.image.source="https://github.com/fluxapps/flux-markdown-to-html-converter-rest-api"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

RUN apk add --no-cache libstdc++ && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev openssl-dev && \
    (mkdir -p /usr/src/php/ext/swoole && cd /usr/src/php/ext/swoole && wget -O - $SWOOLE_SOURCE_URL | tar -xz --strip-components=1) && \
    docker-php-ext-configure swoole --enable-openssl --enable-swoole-curl --enable-swoole-json && \
    docker-php-ext-install -j$(nproc) swoole && \
    docker-php-source delete && \
    apk del .build-deps

USER www-data:www-data

EXPOSE 9501

ENTRYPOINT ["/flux-markdown-to-html-converter-rest-api/bin/server.php"]

COPY --from=build /flux-markdown-to-html-converter-rest-api /flux-markdown-to-html-converter-rest-api

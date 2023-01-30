<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Server;

use FluxMarkdownToHtmlConverterApi\Adapter\Api\MarkdownToHtmlConverterApiConfigDto;

class MarkdownToHtmlConverterRestApiServerConfigDto
{

    private function __construct(
        public readonly MarkdownToHtmlConverterApiConfigDto $markdown_to_html_converter_api_config,
        public readonly ?string $https_cert,
        public readonly ?string $https_key,
        public readonly string $listen,
        public readonly int $port
    ) {

    }


    public static function new(
        MarkdownToHtmlConverterApiConfigDto $markdown_to_html_converter_api_config,
        ?string $https_cert = null,
        ?string $https_key = null,
        ?string $listen = null,
        ?int $port = null
    ) : static {
        return new static(
            $markdown_to_html_converter_api_config,
            $https_cert,
            $https_key,
            $listen ?? "0.0.0.0",
            $port ?? 9501
        );
    }


    public static function newFromEnv() : static
    {
        return static::new(
            MarkdownToHtmlConverterApiConfigDto::newFromEnv(),
            $_ENV["FLUX_MARKDOWN_TO_HTML_CONVERTER_REST_API_SERVER_HTTPS_CERT"] ?? null,
            $_ENV["FLUX_MARKDOWN_TO_HTML_CONVERTER_REST_API_SERVER_HTTPS_KEY"] ?? null,
            $_ENV["FLUX_MARKDOWN_TO_HTML_CONVERTER_REST_API_SERVER_LISTEN"] ?? null,
            $_ENV["FLUX_MARKDOWN_TO_HTML_CONVERTER_REST_API_SERVER_PORT"] ?? null
        );
    }
}

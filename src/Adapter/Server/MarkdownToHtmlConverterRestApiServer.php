<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Server;

use FluxMarkdownToHtmlConverterRestApi\Adapter\Api\MarkdownToHtmlConverterRestApi;
use FluxRestApi\Adapter\Api\RestApi;
use FluxRestApi\Adapter\Route\Collector\RouteCollector;
use FluxRestApi\Adapter\Server\SwooleServerConfigDto;

class MarkdownToHtmlConverterRestApiServer
{

    private function __construct(
        private readonly RestApi $rest_api,
        private readonly RouteCollector $route_collector,
        private readonly SwooleServerConfigDto $swoole_server_config
    ) {

    }


    public static function new(
        ?MarkdownToHtmlConverterRestApiServerConfigDto $markdown_to_html_converter_rest_api_server_config = null
    ) : static {
        $markdown_to_html_converter_rest_api_server_config ??= MarkdownToHtmlConverterRestApiServerConfigDto::newFromEnv();

        return new static(
            RestApi::new(),
            MarkdownToHtmlConverterRestApiServerRouteCollector::new(
                MarkdownToHtmlConverterRestApi::new(
                    $markdown_to_html_converter_rest_api_server_config->markdown_to_html_converter_rest_api_config
                )
            ),
            SwooleServerConfigDto::new(
                $markdown_to_html_converter_rest_api_server_config->https_cert,
                $markdown_to_html_converter_rest_api_server_config->https_key,
                $markdown_to_html_converter_rest_api_server_config->listen,
                $markdown_to_html_converter_rest_api_server_config->port
            )
        );
    }


    public function init() : void
    {
        $this->rest_api->initSwooleServer(
            $this->route_collector,
            null,
            $this->swoole_server_config
        );
    }
}

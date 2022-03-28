<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Server;

use FluxMarkdownToHtmlConverterRestApi\Libs\FluxMarkdownToHtmlConverterApi\Adapter\Api\MarkdownToHtmlConverterApi;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Server\SwooleRestApiServer;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Server\SwooleRestApiServerConfigDto;

class MarkdownToHtmlConverterRestApiServer
{

    private function __construct(
        private readonly SwooleRestApiServer $swoole_rest_api_server
    ) {

    }


    public static function new(
        ?MarkdownToHtmlConverterRestApiServerConfigDto $markdown_to_html_converter_rest_api_server_config = null
    ) : static {
        $markdown_to_html_converter_rest_api_server_config ??= MarkdownToHtmlConverterRestApiServerConfigDto::newFromEnv();

        return new static(
            SwooleRestApiServer::new(
                MarkdownToHtmlConverterRestApiServerRouteCollector::new(
                    MarkdownToHtmlConverterApi::new(
                        $markdown_to_html_converter_rest_api_server_config->markdown_to_html_converter_api_config
                    )
                ),
                null,
                SwooleRestApiServerConfigDto::new(
                    $markdown_to_html_converter_rest_api_server_config->https_cert,
                    $markdown_to_html_converter_rest_api_server_config->https_key,
                    $markdown_to_html_converter_rest_api_server_config->listen,
                    $markdown_to_html_converter_rest_api_server_config->port
                )
            )
        );
    }


    public function init() : void
    {
        $this->swoole_rest_api_server->init();
    }
}

<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Server;

use FluxMarkdownToHtmlConverterRestApi\Libs\FluxMarkdownToHtmlConverterApi\Adapter\Api\MarkdownToHtmlConverterApi;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Collector\FolderRouteCollector;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Handler\SwooleHandler;
use Swoole\Http\Server;

class MarkdownToHtmlConverterRestApiServer
{

    private function __construct(
        private readonly MarkdownToHtmlConverterRestApiServerConfigDto $markdown_to_html_converter_rest_api_server_config,
        private readonly SwooleHandler $swoole_handler
    ) {

    }


    public static function new(
        ?MarkdownToHtmlConverterRestApiServerConfigDto $markdown_to_html_converter_rest_api_server_config = null
    ) : static {
        $markdown_to_html_converter_rest_api_server_config ??= MarkdownToHtmlConverterRestApiServerConfigDto::newFromEnv();

        return new static(
            $markdown_to_html_converter_rest_api_server_config,
            SwooleHandler::new(
                FolderRouteCollector::new(
                    __DIR__ . "/../Route",
                    [
                        MarkdownToHtmlConverterApi::new(
                            $markdown_to_html_converter_rest_api_server_config->markdown_to_html_converter_api_config
                        )
                    ]
                )
            )
        );
    }


    public function init() : void
    {
        $options = [];
        $sock_type = SWOOLE_TCP;

        if ($this->markdown_to_html_converter_rest_api_server_config->https_cert !== null) {
            $options += [
                "ssl_cert_file" => $this->markdown_to_html_converter_rest_api_server_config->https_cert,
                "ssl_key_file"  => $this->markdown_to_html_converter_rest_api_server_config->https_key
            ];
            $sock_type += SWOOLE_SSL;
        }

        $server = new Server($this->markdown_to_html_converter_rest_api_server_config->listen, $this->markdown_to_html_converter_rest_api_server_config->port, SWOOLE_PROCESS, $sock_type);

        $server->set($options);

        $server->on("request", [$this->swoole_handler, "handle"]);

        $server->start();
    }
}

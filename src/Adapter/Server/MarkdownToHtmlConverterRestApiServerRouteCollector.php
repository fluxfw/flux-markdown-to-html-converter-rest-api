<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Server;

use FluxMarkdownToHtmlConverterApi\Adapter\Api\MarkdownToHtmlConverterApi;
use FluxMarkdownToHtmlConverterRestApi\Adapter\Route\ConvertMultipleRoute;
use FluxMarkdownToHtmlConverterRestApi\Adapter\Route\ConvertRoute;
use FluxRestApi\Adapter\Route\Collector\RouteCollector;

class MarkdownToHtmlConverterRestApiServerRouteCollector implements RouteCollector
{

    private function __construct(
        private readonly MarkdownToHtmlConverterApi $markdown_to_html_converter_api
    ) {

    }


    public static function new(
        MarkdownToHtmlConverterApi $markdown_to_html_converter_api
    ) : static {
        return new static(
            $markdown_to_html_converter_api
        );
    }


    public function collectRoutes() : array
    {
        return [
            ConvertRoute::new(
                $this->markdown_to_html_converter_api
            ),
            ConvertMultipleRoute::new(
                $this->markdown_to_html_converter_api
            )
        ];
    }
}

<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Server;

use FluxMarkdownToHtmlConverterRestApi\Adapter\Api\MarkdownToHtmlConverterRestApi;
use FluxMarkdownToHtmlConverterRestApi\Adapter\Route\ConvertMultipleRoute;
use FluxMarkdownToHtmlConverterRestApi\Adapter\Route\ConvertRoute;
use FluxRestApi\Adapter\Route\Collector\RouteCollector;

class MarkdownToHtmlConverterRestApiServerRouteCollector implements RouteCollector
{

    private function __construct(
        private readonly MarkdownToHtmlConverterRestApi $markdown_to_html_converter_rest_api
    ) {

    }


    public static function new(
        MarkdownToHtmlConverterRestApi $markdown_to_html_converter_rest_api
    ) : static {
        return new static(
            $markdown_to_html_converter_rest_api
        );
    }


    public function collectRoutes() : array
    {
        return [
            ConvertRoute::new(
                $this->markdown_to_html_converter_rest_api
            ),
            ConvertMultipleRoute::new(
                $this->markdown_to_html_converter_rest_api
            )
        ];
    }
}

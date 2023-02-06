<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Api;

use FluxMarkdownToHtmlConverterRestApi\Adapter\Html\HtmlDto;
use FluxMarkdownToHtmlConverterRestApi\Adapter\Markdown\MarkdownDto;
use FluxMarkdownToHtmlConverterRestApi\Service\Converter\Port\ConverterService;

class MarkdownToHtmlConverterRestApi
{

    private function __construct(
        private readonly MarkdownToHtmlConverterRestApiConfigDto $markdown_to_html_converter_rest_api_config
    ) {

    }


    public static function new(
        ?MarkdownToHtmlConverterRestApiConfigDto $markdown_to_html_converter_rest_api_config = null
    ) : static {
        return new static(
            $markdown_to_html_converter_rest_api_config ?? MarkdownToHtmlConverterRestApiConfigDto::newFromEnv()
        );
    }


    public function convert(MarkdownDto $markdown) : HtmlDto
    {
        return $this->getConverterService()
            ->convert(
                $markdown
            );
    }


    /**
     * @param MarkdownDto[] $markdowns
     *
     * @return HtmlDto[]
     */
    public function convertMultiple(array $markdowns) : array
    {
        return $this->getConverterService()
            ->convertMultiple(
                $markdowns
            );
    }


    private function getConverterService() : ConverterService
    {
        return ConverterService::new(
            $this->markdown_to_html_converter_rest_api_config->color_config
        );
    }
}

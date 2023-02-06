<?php

namespace FluxMarkdownToHtmlConverterRestApi\Service\Converter\Port;

use FluxMarkdownToHtmlConverterRestApi\Adapter\Color\ColorConfigDto;
use FluxMarkdownToHtmlConverterRestApi\Adapter\Html\HtmlDto;
use FluxMarkdownToHtmlConverterRestApi\Adapter\Markdown\MarkdownDto;
use FluxMarkdownToHtmlConverterRestApi\Service\Converter\Command\ConvertCommand;

class ConverterService
{

    private function __construct(
        private readonly ColorConfigDto $color_config
    ) {

    }


    public static function new(
        ColorConfigDto $color_config
    ) : static {
        return new static(
            $color_config
        );
    }


    public function convert(MarkdownDto $markdown) : HtmlDto
    {
        return ConvertCommand::new(
            $this->color_config
        )
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
        return ConvertCommand::new(
            $this->color_config
        )
            ->convertMultiple(
                $markdowns
            );
    }
}

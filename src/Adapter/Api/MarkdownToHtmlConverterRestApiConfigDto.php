<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Api;

use FluxMarkdownToHtmlConverterRestApi\Adapter\Color\ColorConfigDto;

class MarkdownToHtmlConverterRestApiConfigDto
{

    private function __construct(
        public readonly ColorConfigDto $color_config
    ) {

    }


    public static function new(
        ColorConfigDto $color_config
    ) : static {
        return new static(
            $color_config
        );
    }


    public static function newFromEnv() : static
    {
        return static::new(
            ColorConfigDto::newFromEnv()
        );
    }
}

<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Markdown;

class MarkdownDto
{

    private function __construct(
        public readonly string $markdown
    ) {

    }


    public static function new(
        string $markdown
    ) : static {
        return new static(
            $markdown
        );
    }


    public static function newFromObject(
        object $markdown
    ) : static {
        return static::new(
            $markdown->markdown
        );
    }
}

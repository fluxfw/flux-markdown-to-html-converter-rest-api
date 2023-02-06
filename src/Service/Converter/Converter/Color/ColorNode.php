<?php

namespace FluxMarkdownToHtmlConverterRestApi\Service\Converter\Converter\Color;

use League\CommonMark\Node\Inline\AbstractInline;

class ColorNode extends AbstractInline
{

    private function __construct(
        public readonly string $name
    ) {
        parent::__construct();
    }


    public static function new(
        string $name
    ) : static {
        return new static(
            $name
        );
    }
}

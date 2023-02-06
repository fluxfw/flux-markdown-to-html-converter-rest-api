<?php

namespace FluxMarkdownToHtmlConverterRestApi\Service\Converter\Converter;

use FluxMarkdownToHtmlConverterRestApi\Adapter\Color\ColorConfigDto;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class CustomConverter extends MarkdownConverter
{

    private function __construct(
        private readonly ColorConfigDto $color_config
    ) {
        $environment = new Environment([
            "html_input"         => "strip",
            "allow_unsafe_links" => false
        ]);

        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $environment->addExtension(CustomExtension::new(
            $this->color_config
        ));

        parent::__construct($environment);
    }


    public static function new(
        ColorConfigDto $color_config
    ) : static {
        return new static(
            $color_config
        );
    }
}

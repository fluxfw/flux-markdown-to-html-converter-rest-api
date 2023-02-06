<?php

namespace FluxMarkdownToHtmlConverterRestApi\Service\Converter\Converter\Color;

use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

class ColorParser implements InlineParserInterface
{

    private function __construct()
    {

    }


    public static function new() : static
    {
        return new static();
    }


    public function getMatchDefinition() : InlineParserMatch
    {
        return InlineParserMatch::regex("@color-([A-Za-z0-9_]+)\((.+)\)");
    }


    public function parse(InlineParserContext $inlineContext) : bool
    {
        $inlineContext->getCursor()->advanceBy($inlineContext->getFullMatchLength());
        $matches = $inlineContext->getSubMatches();

        $node = ColorNode::new(
            $matches[0]
        );
        $node->appendChild(new Text($matches[1]));
        $inlineContext->getContainer()->appendChild($node);

        return true;
    }
}

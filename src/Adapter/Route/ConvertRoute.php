<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Route;

use FluxMarkdownToHtmlConverterRestApi\Libs\FluxMarkdownToHtmlConverterApi\Adapter\Api\MarkdownToHtmlConverterApi;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxMarkdownToHtmlConverterApi\Adapter\Markdown\MarkdownDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Body\JsonBodyDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Body\TextBodyDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Body\Type\DefaultBodyType;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Method\DefaultMethod;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Method\Method;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Route\Route;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Server\ServerRequestDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Server\ServerResponseDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Status\DefaultStatus;

class ConvertRoute implements Route
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


    public function getDocuRequestBodyTypes() : ?array
    {
        return [
            DefaultBodyType::JSON
        ];
    }


    public function getDocuRequestQueryParams() : ?array
    {
        return null;
    }


    public function getMethod() : Method
    {
        return DefaultMethod::POST;
    }


    public function getRoute() : string
    {
        return "/convert";
    }


    public function handle(ServerRequestDto $request) : ?ServerResponseDto
    {
        if (!($request->getParsedBody() instanceof JsonBodyDto)) {
            return ServerResponseDto::new(
                TextBodyDto::new(
                    "No json body"
                ),
                DefaultStatus::_400
            );
        }

        return ServerResponseDto::new(
            JsonBodyDto::new(
                $this->markdown_to_html_converter_api->convert(
                    MarkdownDto::newFromData(
                        $request->getParsedBody()->getData()
                    )
                )
            )
        );
    }
}

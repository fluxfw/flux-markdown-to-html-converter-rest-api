<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Route;

use FluxMarkdownToHtmlConverterRestApi\Libs\FluxMarkdownToHtmlConverterApi\Adapter\Api\MarkdownToHtmlConverterApi;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxMarkdownToHtmlConverterApi\Adapter\Markdown\MarkdownDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Body\DefaultBodyType;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Body\JsonBodyDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Body\TextBodyDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Method\DefaultMethod;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Method\Method;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Request\RequestDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Response\ResponseDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Route\Route;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Status\DefaultStatus;

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


    public function handle(RequestDto $request) : ?ResponseDto
    {
        if (!($request->getParsedBody() instanceof JsonBodyDto)) {
            return ResponseDto::new(
                TextBodyDto::new(
                    "No json body"
                ),
                DefaultStatus::_400
            );
        }

        return ResponseDto::new(
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

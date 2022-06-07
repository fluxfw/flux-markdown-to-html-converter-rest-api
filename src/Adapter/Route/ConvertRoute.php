<?php

namespace FluxMarkdownToHtmlConverterRestApi\Adapter\Route;

use FluxMarkdownToHtmlConverterRestApi\Libs\FluxMarkdownToHtmlConverterApi\Adapter\Api\MarkdownToHtmlConverterApi;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxMarkdownToHtmlConverterApi\Adapter\Html\HtmlDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxMarkdownToHtmlConverterApi\Adapter\Markdown\MarkdownDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Body\JsonBodyDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Body\TextBodyDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Body\Type\DefaultBodyType;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Method\DefaultMethod;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Method\Method;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Route\Documentation\RouteContentTypeDocumentationDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Route\Documentation\RouteDocumentationDto;
use FluxMarkdownToHtmlConverterRestApi\Libs\FluxRestApi\Adapter\Route\Documentation\RouteResponseDocumentationDto;
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


    public function getDocumentation() : ?RouteDocumentationDto
    {
        return RouteDocumentationDto::new(
            $this->getRoute(),
            $this->getMethod(),
            "Convert markdown to html",
            null,
            null,
            null,
            [
                RouteContentTypeDocumentationDto::new(
                    DefaultBodyType::JSON,
                    MarkdownDto::class,
                    "Markdown"
                )
            ],
            [
                RouteResponseDocumentationDto::new(
                    DefaultBodyType::JSON,
                    null,
                    HtmlDto::class,
                    "Html"
                ),
                RouteResponseDocumentationDto::new(
                    DefaultBodyType::TEXT,
                    DefaultStatus::_400,
                    null,
                    "No json body"
                )
            ]
        );
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
        if (!($request->parsed_body instanceof JsonBodyDto)) {
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
                    MarkdownDto::newFromObject(
                        $request->parsed_body->data
                    )
                )
            )
        );
    }
}
